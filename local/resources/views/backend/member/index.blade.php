@extends('layouts.backend.app')

@section('head')
@endsection

@section('css')
@endsection

@section('conten')
    @include('backend.navbar.navbar_mobile')
    <div class="flex overflow-hidden">

        @include('backend.navbar.navbar')
        <div class="content">
            @include('backend.navbar.top_bar')
            {{-- BEGIN TABLE --}}
            <table id="myTable" class="display">
                <thead>
                    <tr>
                        <th>Column 1</th>
                        <th>Column 2</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Row 1 Data 1</td>
                        <td>Row 1 Data 2</td>
                    </tr>
                    <tr>
                        <td>Row 2 Data 1</td>
                        <td>Row 2 Data 2</td>
                    </tr>
                </tbody>
            </table>
            {{-- END TABLE --}}
        </div>
    </div>
@endsection


@section('script')
    {{-- BEGIN DataTable --}}

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
    {{-- BEGIN DataTable --}}
@endsection
