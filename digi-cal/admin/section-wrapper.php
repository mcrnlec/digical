<?php
if (!function_exists('digical_section_wrapper')) {
    function digical_section_wrapper($active, $content_html) {
        global $wpdb;

        // Menus
        $menu_items = [
            'General'  => 'digical',
            'Days'     => 'digical-days',
            'Venues'   => 'digical-venues',
            'Speakers' => 'digical-speakers',
        ];
        $current_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : '';

        // Fetch days from DB (not CSV)
        $table = $wpdb->prefix . 'digical_days';
        $days = $wpdb->get_results("
            SELECT id, date
            FROM `$table`
            ORDER BY CONCAT(SUBSTR(date,5,4),SUBSTR(date,3,2),SUBSTR(date,1,2)) ASC
        ", ARRAY_A) ?: [];

        // Label helper
        $label = function($raw) {
            $t = preg_replace('/\D+/', '', (string)$raw);
            return preg_match('/^(\d{2})(\d{2})(\d{4})$/', $t, $m) ? "{$m[1]}.{$m[2]}.{$m[3]}" : esc_html($raw);
        };

        echo '<style>
            .digical-flex-container {
                display: flex;
                margin-top: 20px;
                min-height: 70vh;
                gap: 32px;
                padding: 0 40px;
            }
            
            .digical-sidebar {
                box-sizing: border-box;
                width: 220px;
                flex-shrink: 0;
                background: white;
                border-radius: 12px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                padding: 28px 16px;
                display: flex;
                flex-direction: column;
                overflow-y: auto;
                border: 1px solid #e8eef7;
            }
            
            .digical-sidebar ul {
                list-style: none;
                margin: 0;
                padding-left: 0;
            }
            
            .digical-sidebar li {
                margin-bottom: 8px;
            }
            
            .digical-link {
                display: block;
                padding: 12px 16px;
                border-radius: 10px;
                color: #1a202c;
                text-decoration: none;
                transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                box-sizing: border-box;
                font-weight: 600;
                font-size: 14px;
            }
            
            .digical-link:hover {
                background: #f0f4f8;
                color: #1f2937;
                transform: translateX(4px);
            }
            
            .digical-link.active {
                background: #3b82f6;
                color: white;
                font-weight: 700;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            }
            
            .digical-content-area {
                flex: 1;
                min-width: 0;
            }
            
            .digical-days-row {
                display: flex;
                align-items: center;
                gap: 8px;
                width: 100%;
                box-sizing: border-box;
                margin-bottom: 4px;
            }
            
            .digical-days-row .digical-link {
                flex: 1;
                min-width: 0;
            }
            
            .digical-days-toggle {
                flex: 0 0 40px;
                text-align: center;
                background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
                border: 2px solid #e8eef7;
                border-radius: 10px;
                padding: 10px 0;
                cursor: pointer;
                line-height: 1;
                box-sizing: border-box;
                transition: all 0.3s ease;
                font-weight: 600;
                color: #3b82f6;
            }
            
            .digical-days-toggle:hover {
                background: #f0f4f8;
                border-color: #3b82f6;
                color: #3b82f6;
                transform: scale(1.05);
            }

            .digical-days-toggle.active {
                background: #3b82f6;
                border-color: #3b82f6;
                color: white;
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            }
            
            .digical-days-toggle .caret {
                display: inline-block;
                transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                font-size: 14px;
                font-weight: 700;
            }
            
            .digical-days-toggle[aria-expanded="false"] .caret {
                transform: rotate(-90deg);
            }
            
            .digical-days-sublist {
                margin: 12px 0 0 0;
                padding: 12px;
                background: linear-gradient(135deg, #f8fafc 0%, #f0f4f8 100%);
                border-radius: 10px;
                border: 1px solid #e8eef7;
                list-style: none;
                padding-left: 8px;
            }
            
            .digical-days-sublist li {
                margin: 6px 0;
            }
            
            .digical-days-sublist a,
            .digical-days-sublist a:link,
            .digical-days-sublist a:visited,
            .digical-days-sublist a:hover,
            .digical-days-sublist a:active {
                display: block;
                padding: 10px 14px 10px 28px;
                color: #1a202c !important;
                text-decoration: none !important;
                background: transparent !important;
                font-weight: 600 !important;
                border-radius: 8px !important;
                box-shadow: none !important;
                font-size: 13px;
                transition: all 0.3s ease !important;
            }
            
            .digical-days-sublist a:hover {
                background: rgba(59, 130, 246, 0.1) !important;
                color: #3b82f6 !important;
                padding-left: 32px !important;
            }
            
            .digical-days-sublist a.active {
                background: #3b82f6 !important;
                color: white !important;
                padding-left: 28px !important;
                font-weight: 700 !important;
            }
            
            .is-hidden {
                display: none;
            }
            
            @media (max-width: 1024px) {
                .digical-flex-container {
                    gap: 24px;
                    padding: 0 24px;
                }
                
                .digical-sidebar {
                    width: 180px;
                    padding: 20px 12px;
                }
            }
            
            @media (max-width: 768px) {
                .digical-flex-container {
                    flex-direction: column;
                    gap: 20px;
                    padding: 0 16px;
                }
                
                .digical-sidebar {
                    width: 100%;
                    flex-direction: row;
                    flex-wrap: wrap;
                    padding: 16px;
                }
                
                .digical-sidebar ul {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 8px;
                    width: 100%;
                }
                
                .digical-sidebar li {
                    margin-bottom: 0;
                }
                
                .digical-link {
                    padding: 10px 14px;
                    font-size: 13px;
                }
                
                .digical-days-row {
                    width: 100%;
                    margin-bottom: 0;
                }
                
                .digical-content-area {
                    width: 100%;
                }
            }
        </style>';

        echo '<div class="digical-flex-container">
            <nav class="digical-sidebar">
                <ul>';

        foreach ($menu_items as $labelTxt => $slug) {
            echo '<li>';
            if ($labelTxt !== 'Days') {
                $url = admin_url('admin.php?page='.$slug);
                $active_class = ($current_page === $slug) ? 'active' : '';
                echo '<a href="'.esc_url($url).'" class="digical-link '.$active_class.'">'.esc_html($labelTxt).'</a>';
            } else {
                $days_url  = admin_url('admin.php?page=digical-days');
                $is_active = ($current_page === 'digical-days') ? 'active' : '';
                // Check if any day page is active
                $is_day_page_active = (strpos($current_page, 'digical-day-') === 0) ? 'active' : '';
                $toggle_active_class = ($is_active || $is_day_page_active) ? 'active' : '';
                
                echo '<div class="digical-days-row">';
                echo    '<a href="'.esc_url($days_url).'" class="digical-link '.$is_active.'">'.esc_html__('Days','digical').'</a>';
                echo    '<button type="button" class="digical-days-toggle '.$toggle_active_class.'" data-target="#digical-days-list" aria-expanded="true" aria-controls="digical-days-list"><span class="caret">▾</span></button>';
                echo '</div>';

                echo '<ul id="digical-days-list" class="digical-days-sublist">';
                foreach ($days as $d) {
                    $day_slug  = 'digical-day-'.$d['id']; // use id for slug
                    $day_url   = admin_url('admin.php?page='.$day_slug);
                    $day_active_class = ($current_page === $day_slug) ? 'active' : '';
                    echo '<li><a href="'.esc_url($day_url).'" class="digical-link '.$day_active_class.'">'.esc_html($label($d['date'])).'</a></li>';
                }
                echo '</ul>';
            }
            echo '</li>';
        }

        echo '   </ul>
            </nav>
            <div class="digical-content-area">'.$content_html.'</div>
        </div>';

        echo '<script>
        (function(){
          const btn  = document.querySelector(".digical-days-toggle[data-target=\"#digical-days-list\"]");
          const list = document.getElementById("digical-days-list");
          if(!btn || !list) return;
          const KEY = "digical_days_collapsed";
          if(localStorage.getItem(KEY) === "1"){
            list.classList.add("is-hidden");
            btn.setAttribute("aria-expanded","false");
            btn.classList.remove("active");
          } else {
            btn.setAttribute("aria-expanded","true");
            btn.classList.add("active");
          }
          btn.addEventListener("click", function(ev){
            ev.preventDefault();
            ev.stopPropagation();
            const open = btn.getAttribute("aria-expanded") === "true";
            btn.setAttribute("aria-expanded", open ? "false" : "true");
            btn.classList.toggle("active", !open);
            list.classList.toggle("is-hidden", open);
            localStorage.setItem(KEY, open ? "1" : "0");
          }, {passive:false});
        })();
        </script>';
    }
}
?>