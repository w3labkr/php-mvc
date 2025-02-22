<?php

namespace App\Core;

class View {
    /**
     * 지정한 뷰 파일을 렌더링하고 결과를 반환합니다.
     *
     * @param string $view 뷰 파일 이름 (확장자 제외)
     * @param array $data 뷰에 전달할 데이터
     * @return string 렌더링된 HTML 출력
     */
    public static function render($view, $data = []) {
        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        if (file_exists($viewFile)) {
            extract($data);
            ob_start();
            include $viewFile;
            return ob_get_clean();
        }
        return "View file not found.";
    }
}
