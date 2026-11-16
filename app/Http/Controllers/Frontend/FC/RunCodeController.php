<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

/*
| รูปแบบรหัส (ยาว 15): <prefix><ปี พ.ศ. 2 หลัก><เดือน><วัน>-<เลขรัน 6 หลัก>  เริ่มใหม่ทุกวัน
| ใช้ตาราง running_counters (1 แถวต่อ prefix ต่อวัน) ด้วย UPDATE ... LAST_INSERT_ID() ซึ่งเป็น atomic
| ไม่ต้องอยู่ใน transaction และไม่ซ้ำแม้เรียกพร้อมกัน
*/
class RunCodeController extends Controller
{
  const DIGITS = 6;

  public static function db_code_order()
  {
    return self::next('ON' . self::ymd() . '-', ['db_code_order' => 'code']);
  }

  public static function db_code_bonus($type_id_fk)
  {
    return self::next('S' . $type_id_fk . self::ymd() . '-', ['db_code_bonus' => 'code']);
  }

  public static function db_code_pv()
  {
    return self::next('PJ' . self::ymd() . '-', ['db_code_pv' => 'code', 'jang_pv' => 'code']);
  }

  public static function db_code_wallet()
  {
    return self::next('WL' . self::ymd() . '-', ['db_code_wallet' => 'code']);
  }

  private static function ymd()
  {
    return substr(date('Y') + 543, -2) . date('m') . date('d');
  }

  /**
   * @param string $prefix  เช่น ON690116-
   * @param array  $legacy  [ตาราง => คอลัมน์] ของรหัสเดิม ใช้เป็นค่าตั้งต้นเมื่อสร้างตัวนับของ prefix นี้ครั้งแรก
   *                        (กันซ้ำกับรหัสที่ออกไปแล้วในวันที่ deploy)
   */
  private static function next($prefix, array $legacy)
  {
    $seed = self::legacyMax($prefix, $legacy);

    DB::table('running_counters')->insertOrIgnore([
      'prefix' => $prefix,
      'current_number' => $seed,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    DB::update(
      'UPDATE running_counters SET current_number = LAST_INSERT_ID(current_number + 1), updated_at = ? WHERE prefix = ?',
      [now(), $prefix]
    );
    $next = (int) DB::selectOne('SELECT LAST_INSERT_ID() AS n')->n;

    return $prefix . str_pad($next, self::DIGITS, '0', STR_PAD_LEFT);
  }

  // เลขรันสูงสุดของ prefix ในตารางเดิม (0 ถ้าไม่มี) — ทำเฉพาะตอนยังไม่มีตัวนับของ prefix นั้น
  private static function legacyMax($prefix, array $legacy)
  {
    if (DB::table('running_counters')->where('prefix', $prefix)->exists()) {
      return 0;
    }

    $max = 0;
    foreach ($legacy as $table => $col) {
      $last = DB::table($table)->where($col, 'like', $prefix . '%')->max($col);
      if ($last) {
        $max = max($max, (int) substr($last, strlen($prefix)));
      }
    }
    return $max;
  }
}
