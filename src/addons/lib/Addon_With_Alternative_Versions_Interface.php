<?php

namespace cybot\cookiebot\addons\lib;

interface Addon_With_Alternative_Versions_Interface {
	/**
	 * @return array
	 */
	public function get_alternative_addon_versions();
}
