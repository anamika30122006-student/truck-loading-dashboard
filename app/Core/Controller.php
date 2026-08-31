<?php
namespace App\Core;

class Controller {
    protected function render($view, $data = [], $layout = 'layouts/header') {
        extract($data);
        $viewsDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR;
        
        $flash = Session::getFlash();
        $currentPage = $data['activePage'] ?? '';

        // Buffer view content
        ob_start();
        include $viewsDir . $view . '.php';
        $content = ob_get_clean();

        // Output complete page with layouts
        include $viewsDir . 'layouts/header.php';
        include $viewsDir . 'layouts/sidebar.php';
        echo '<main class="main-content">';
        include $viewsDir . 'layouts/topbar.php';
        echo '<div class="content-body">';
        if ($flash) {
            $alertClass = ($flash['type'] === 'error') ? 'alert-danger' : 'alert-' . $flash['type'];
            echo '<div class="alert ' . $alertClass . ' alert-dismissible fade show" role="alert">
                    <div class="alert-icon"><i class="ph ph-info"></i></div>
                    <div class="alert-text">' . htmlspecialchars($flash['message']) . '</div>
                    <button type="button" class="btn-close" onclick="this.parentElement.remove();">&times;</button>
                  </div>';
        }
        echo $content;
        echo '</div>'; // close content-body
        include $viewsDir . 'layouts/footer.php';
        echo '</main>';
        echo '</div>'; // close app-container
        include $viewsDir . 'layouts/scripts.php';
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url, $flashType = null, $flashMessage = null) {
        if ($flashType && $flashMessage) {
            Session::setFlash($flashType, $flashMessage);
        }
        header("Location: " . $url);
        exit;
    }
}
