<?php use \Core\Format; ?><fieldset>
	<legend>Interaktive Karte</legend>
	Auf dieser Karte siehst du alle Bahnhöfe und Strecke, die du befahren kannst.
	Außerdem siehst du deine Züge und alle Züge deiner Mitspieler, die gerade auf der Karte unterwegs sind.
	Eventuelle Beeinträchtigungen des Netzes werden ebenfalls auf der Karte dargestellt.
</fieldset>

<div class="Center">
	<? \Core\i::Module()->includeTemplate('mapView',array('mapContent'=>\Core\i::Module()->getVarCache('svgMap'))) ?>
</div>