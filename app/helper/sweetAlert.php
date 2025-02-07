<?php
namespace App\helper;

class SweetAlert {
    public static function setMessage($title, $text, $status, $redirectPage) {
        $_SESSION['msgSweetAlert'] = [
            'title' => $title,
            'text' => $text,
            'status' => $status
        ];
        self::display($redirectPage);
        exit;
    }

    public static function display($redirectUrl = "") {
        if (isset($_SESSION['msgSweetAlert'])) {
            $title = addslashes($_SESSION['msgSweetAlert']['title']);
            $text = addslashes($_SESSION['msgSweetAlert']['text']);
            $status = $_SESSION['msgSweetAlert']['status'];

            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: '$title',
                        text: '$text',
                        icon: '$status',
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href = '$redirectUrl';
                    });
                });
            </script>";

            unset($_SESSION['msgSweetAlert']);
        }
    }
}
