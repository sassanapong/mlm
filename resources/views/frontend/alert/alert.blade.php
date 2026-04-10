<!doctype html>
<html lang="en">

<head>

</head>

<body data-sidebar="dark">

    <!-- Script Zone -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const url = '/';
        $(function() {
            Swal.fire({
                title: "เกิดข้อผิดพลาด",
                text: "วันที่รักษายอดไม่เพียงพอ",
                icon: "error",
                allowOutsideClick: false,
            }).then((result) => {
                if (url == '') {
                    window.location = window.location.href;
                } else {
                    window.location = url
                }
            });
        })
    </script>

</body>

</html>