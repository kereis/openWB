<?php
if(isset($_GET["prometheus"])) {
	$registry = \Prometheus\CollectorRegistry::getDefault();

	\Prometheus\CollectorRegistry::getDefault()
		->getOrRegisterGauge('openwb', 'current_charging_power_joules', 'Current charging power in Joules',)
		->set((explode(PHP_EOL, file_get_contents('/var/www/html/openWB/ramdisk/llkwh'))[0] * 1000 * 3600));
	
	\Prometheus\CollectorRegistry::getDefault()
		->getOrRegisterGauge('openwb', 'charging_point_enabled', 'Enable state of a charging point as boolean', ['charge_point_number'])
		->set(explode(PHP_EOL, file_get_contents('/var/www/html/openWB/ramdisk/lp1enabled'))[0], ['1']);

	\Prometheus\CollectorRegistry::getDefault()
		->getOrRegisterGauge('openwb', 'charging_point_charging_active', 'Charging active state of a charging point as boolean', ['charge_point_number'])
		->set(explode(PHP_EOL, file_get_contents('/var/www/html/openWB/ramdisk/ladungaktivlp1'))[0], ['1']);

	\Prometheus\CollectorRegistry::getDefault()
		->getOrRegisterGauge('openwb', 'charging_point_last_active_state', 'Last active state of a charging point as boolean', ['charge_point_number'])
		->set(explode(PHP_EOL, file_get_contents('/var/www/html/openWB/ramdisk/cpulp1waraktiv'))[0], ['1']);

	\Prometheus\CollectorRegistry::getDefault()
		->getOrRegisterGauge('openwb', 'charging_point_cpu_counter', 'CPU counter of a charging point as boolean', ['charge_point_number'])
		->set(explode(PHP_EOL, file_get_contents('/var/www/html/openWB/ramdisk/cpulp1counter'))[0], ['1']);

	\Prometheus\CollectorRegistry::getDefault()
		->getOrRegisterGauge('openwb', 'evse_plug_state', 'Plug state of EVSE connected to the wallbox')
		->set(explode(PHP_EOL, file_get_contents('/var/www/html/openWB/ramdisk/evseplugstate'))[0]);

	$renderer = new RenderTextFormat();
	$result = $renderer->render($registry->getMetricFamilySamples());

	header('Content-type: ' . RenderTextFormat::MIME_TYPE);
	echo $result;
}
?>