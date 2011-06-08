<?php
if (!class_exists('cfct_module_event')) {
	class cfct_module_event extends cfct_build_module {

		public function __construct() {
            // Set global plugin params
            $this->pluginDir		= basename(dirname(__FILE__));
            $this->pluginPath		= WP_PLUGIN_DIR . '/' . $this->pluginDir;
            $this->pluginUrl 		= WP_PLUGIN_URL.'/'.$this->pluginDir;
            // Set plugin options
            $opts = array(
                'url'           => $this->pluginUrl,
                'view'          => $this->pluginPath.'/view.php',
                'description'   => __('Allows to add event into pages', 'carrington-build'),
                'icon'          => $this->pluginDir.'/icon.png'
            );
            // Register new hook for download link
            add_action('page_template', array($this, 'download_ics'), 999);

            // Register new query vars
            add_filter('query_vars', array($this, 'query_vars'));

            // Init new module
            cfct_build_module::__construct('cfct-module-event', __('Event', 'carrington-build'), $opts);
		}

        public function download_ics($template) {
            global $post;

            $ics = get_query_var('ics');
            if (!$ics)
                return $template;

            // Get data
            $meta = array_shift(get_post_meta($post->ID, '_cfct_build_data'));

            if (!isset($meta['data']['modules']['cfct-module-'.$ics]))
                return $template;

            $data = $meta['data']['modules']['cfct-module-'.$ics];
            $event = $this->get_event_var($data);

            $ics = $this->get_ics($event);

            $slug = preg_replace("/[^a-zA-Z0-9 ]/", "", $event['name']);
            $slug = str_replace(" ", "-", trim($slug));

            header("Cache-Control: public");
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=".$slug.'.ics');
            header("Content-Type: application/ics");
            header("Content-Transfer-Encoding: binary");

            echo $ics;

            exit;
        }

        public function query_vars($vars) {
            $vars[] = 'ics';

            return $vars;
        }

        public function get_event_var($data) {
            global $post;

            // Standart params
            $event = array(
                'name' => $data[$this->get_field_name('event_name')],
                'date' => array(
                    'mm' => $data[$this->get_field_name('event_date_mm')],
                    'jj' => $data[$this->get_field_name('event_date_jj')],
                    'aa' => $data[$this->get_field_name('event_date_aa')],
                    'hh' => $data[$this->get_field_name('event_date_hh')],
                    'mn' => $data[$this->get_field_name('event_date_mn')],
                    'tz' => $data[$this->get_field_name('event_date_tz')]
                ),
                'location' => array(
                    'name' => $data[$this->get_field_name('event_location_name')],
                    'address' => $data[$this->get_field_name('event_location_address')],
                    'city' => $data[$this->get_field_name('event_location_city')],
                    'province' => $data[$this->get_field_name('event_location_province')],
                    'state' => $data[$this->get_field_name('event_location_state')],
                    'country' => $data[$this->get_field_name('event_location_country')]
                )
            );

            // Generate timestamp
            $timestamp = mktime(
                        $event['date']['hh'],  // Hour
                        $event['date']['mn'],  // Min
                        0,                     // Sec
                        $event['date']['mm'],  // Month
                        $event['date']['jj'],  // Day
                        $event['date']['aa']   // Year
                    );

            $event['date']['timestamp'] = $timestamp;
            $event['link'] = get_permalink($post->ID).'?ics='.str_replace('cfct-module-', '', $data['module_id']);

            return $event;

        }

		public function display($data) {
            $event = $this->get_event_var($data);

            return $this->load_view($data, compact('event', 'data'));
		}

		public function update($new_data, $old_data) {
			return $new_data;
		}

        public function text($data) {
            return strip_tags($data[$this->get_field_name('event_name')]);
        }

        public function admin_form($data) {
            $html = '';

            $html .= '<div id="cfct-event-fields">';
                /* Event */
                $html .= '<fieldset class="cfct-ftl-border">';
                $html .= '<legend>Event</legend>';
                // Name (event_name)
                $html .= '<label><b>Name</b></label>
                          <input type="text" class="cfct-event-text-field" name="'.$this->get_field_name('event_name').'" value="'.$data[$this->get_field_name('event_name')].'" />';

                // Start date (event_start_date)
                $html .= '<label><b>Date</b></label>
                         '.$this->datetime_fields($data);

                $html .= '</fieldset>';

                /* Location */
                $html .= '<fieldset class="cfct-ftl-border">';
                $html .= '<legend>Location</legend>';
                // Name (event_location_name)
                $html .= '<label><b>Name</b></label>
                          <input type="text" class="cfct-event-text-field" name="'.$this->get_field_name('event_location_name').'" value="'.$data[$this->get_field_name('event_location_name')].'" />';
                // Address (event_location_address)
                $html .= '<label><b>Address</b></label>
                          <input type="text" class="cfct-event-text-field" name="'.$this->get_field_name('event_location_address').'" value="'.$data[$this->get_field_name('event_location_address')].'" />';
                // City (event_location_city)
                $html .= '<label><b>City</b></label>
                          <input type="text" class="cfct-event-text-field" name="'.$this->get_field_name('event_location_city').'" value="'.$data[$this->get_field_name('event_location_city')].'" />';
                // Province (event_location_province)
                $html .= '<label><b>Province</b></label>
                          <input type="text" class="cfct-event-text-field" name="'.$this->get_field_name('event_location_province').'" value="'.$data[$this->get_field_name('event_location_province')].'" />';
                // State (event_location_state)
                $html .= '<label><b>State</b></label>
                          <input type="text" class="cfct-event-text-field" name="'.$this->get_field_name('event_location_state').'" value="'.$data[$this->get_field_name('event_location_state')].'" />';
                // Country (event_location_country)
                $html .= '<label><b>Country</b></label>
                          <input type="text" class="cfct-event-text-field" name="'.$this->get_field_name('event_location_country').'" value="'.$data[$this->get_field_name('event_location_country')].'" />';
                $html .= '</fieldset>';
            
            $html .= '</div>';

            return $html;
        }

        public function datetime_fields($data) {
            global $wp_locale;

            $month = '<select id="mm" name="'.$this->get_field_name('event_date_mm').'">';
                for ( $i = 1; $i < 13; $i++ ) {
                   $month .= '<option value="' . zeroise($i, 2) . '">'.$wp_locale->get_month_abbrev( $wp_locale->get_month( $i ) ).'</option>';
                }
            $month .= '</select>';

            $day = '<input type="text" id="jj" name="'.$this->get_field_name('event_date_jj').'" value="'.$data[$this->get_field_name('event_date_jj')].'" size="2" maxlength="2" autocomplete="off" />';
            $year = '<input type="text" id="aa" name="'.$this->get_field_name('event_date_aa').'" value="'.$data[$this->get_field_name('event_date_aa')].'" size="4" maxlength="4" autocomplete="off" />';
            $hour = '<input type="text" id="hh" name="'.$this->get_field_name('event_date_hh').'" value="'.$data[$this->get_field_name('event_date_hh')].'" size="2" maxlength="2" autocomplete="off" />';
            $minute = '<input type="text" id="mn" name="'.$this->get_field_name('event_date_mn').'" value="'.$data[$this->get_field_name('event_date_mn')].'" size="2" maxlength="2" autocomplete="off" />';
            $zone = $this->get_tz($data);

            return '<div id="timestampdiv"><div class="timestamp-wrap">'.
                    sprintf(__('%1$s%2$s, %3$s @ %4$s : %5$s %6$s'), $month, $day, $year, $hour, $minute, $zone).
                    '</div></div>';
        }

        public function get_ics($event) {
            // Generate time
            $time = date('Ymd', $event['date']['timestamp']).'T'.date('His', $event['date']['timestamp']).'Z';
            $ics  = array(
                "BEGIN:VCALENDAR",
                "PRODID:-//D&H//D&H Events & Presentations 1.0//EN",
                "VERSION:2.0",
                "CALSCALE:GREGORIAN",
                "BEGIN:VEVENT",
                "DTSTART:".$time,
                "DTSTAMP:".$time,
                "LOCATION:".$event['location']['name'].' '.$event['location']['address'].' '.$event['location']['city'],
                "SUMMARY:".$event['name'],
                "END:VEVENT",
                "END:VCALENDAR"
            );

            return implode("\n", $ics);

        }

        public function get_tz($data) {
            // Saved Value
            $v = $data[$this->get_field_name('event_date_tz')];
            
            // List of timezones
            $tz_list = timezone_abbreviations_list();

            // Generate html output
            $tz = '<select name="'.$this->get_field_name('event_date_tz').'" id="tz">';
            foreach ($tz_list as $short => $items) {
                $selected = '';
                if ($v == $short) {
                    $selected = 'selected';
                }
                $tz .= '<option '.$selected.' value="'.$short.'">'.strtoupper($short).'</option>';
            }
            $tz .= '</select>';

            return $tz;
        }

		public function admin_css() {
            return '
                input, select {
                    font-size: 11px;
                    height: 2em;
                    padding: 2px;
                }
                .cfct-module-form .cfct-event-date-field,
                .cfct-module-form .cfct-event-time-hh-field,
                .cfct-module-form .cfct-event-time-mm-field,
                .cfct-module-form .cfct-event-text-field {
                    font-size: 12px;
                    padding: 1px;
                    width: 440px !important;
                }
                .cfct-module-form .cfct-event-time-hh-field,
                .cfct-module-form .cfct-event-time-mm-field {
                    width: 3em !important;
                }
                #cfct-event-fields label b {
                    display: block;
                }
                #timestampdiv input, #namediv input, #poststuff .inside .the-tagcloud {
                    border-color: #CCCCCC !important;
                }
                #timestampdiv select {
                    font-size: 12px;
                    height: 24px !important;
                    padding: 2px !important;
                    vertical-align: baseline !important;
                }
            ';
		}

		public function admin_js() {
		}

	}

    // Register new module
	cfct_build_register_module('cfct-module-event', 'cfct_module_event');
}
 
