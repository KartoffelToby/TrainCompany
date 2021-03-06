<?php
/**
*
* Daten-Klasse, von ihr erben alle Klassen, die Daten brauchen
*	Tochter-Klassen müssen das DataVars-Trait benutzen.
* Datum: 24. Oktober 2012
*
**/
namespace Core;

abstract class Data {
	const DATA_DIR = 'data/';
	
	protected $id, $group, $name;
	protected $properties = array();
	protected $varProperties = array();
	
	/**
	* Lädt alle Daten-Dateien, die im Ordner liegen.
	*
	* @param array $dataFiles - Welche Dateien überhaupt? [optional]
	**/
	public static function loadAllDataFiles($dataFiles = array()) {
		foreach($dataFiles as $currentFile)
		    require_once ROOT_PATH.self::DATA_DIR.$currentFile.'.data.php';
	}

	/**
	* Nimmt neue Objekte an und speichert sie in z.B. einem Array.
	*
	* @param int $id - Eine ID für die Daten.
	* @param self $object - Ein neues Objekt
	* @param bool $group - Die Gruppe des Objekts [optional]
	**/
	public static function addObject($id, $object, $group=false) {
		if(isset(static::$objects[$id])) throw new \Exception('Ein Daten-Objekt mit dieser ID ist bereits gespeichert.', 2011);
		
		$object->setID($id);
		$object->setGroup($group);
		static::$objects[$id] = $object;
		if($group !== false) static::$objectGroups[$group][] = $id;
	}
	
	/**
	* Gibt ein Array mit allen Objekten zurück, die gespeichert sind
	*
	* @param bool $group - Die Gruppe des Objekts [optional]
	* @return array - Liste mit Daten
	**/
	public static function getList($group = false) {
		if($group === false) return static::$objects;
		if(!isset(static::$objectGroups[$group])) throw new \Exception('Diese Daten-Gruppe ist nicht bekannt.', 2012);
		
		$array = array();
		$groupArray = static::$objectGroups[$group];
		foreach($groupArray as $currentID) $array[] = self::getObjectForID($currentID);
		
		return $array;
	}
	
	/**
	* Zählt die Elemente, die gespeichert sind.
	*
	* @return int - Anzahl der Objekte
	**/
	public static function countObjects() {
		return count(self::getList());
	}
	
	/**
	* Gibt das Objekt, passend zur ID wieder aus. Wenn die ID nicht existiert, muss ein Fehler geworfen werden.
	*
	* @param int $id - Die gesuchte ID
	* @return Train
	**/
	public static function getObjectForID($id) {
		if(!self::existObjectForID($id)) throw new \Exception('Kein Daten-Objekt mit dieser ID vorhanden.', 2013);
		
		return static::$objects[$id];
	}
	
	/**
	* Gibt zurück, ob für die ID ein Objekt vorhanden sind.
	*
	* @param int $id - Die gesuchte ID
	* @return bool - true = Vorhanden, false = nicht vorhanden
	**/
	public static function existObjectForID($id) {
		if(isset(static::$objects[$id])) return true;
		
		return false;
	}
	
	/**
	* Erstellt ein neues Daten-Objekt
	* 
	* @param string $name - Name des Objekts [optional]
	**/
	public function __construct($name=NULL) {
		$this->name = $name;
	}
	
	/**
	* Gibt den Namen des Daten-Objekts zurück
	*
	* @return string - Name des Objekts
	**/
	public function getName() {
		return $this->name;
	}
	
	/**
	* Gibt die Properties zurück
	*
	* @return array - Eigenschaften des Daten-Objekts
	**/
	public function getProperties() {
		return $this->properties;
	}
	
	/**
	* Setzt die ID, damit die Klasse später wieder einfacher und aktuell geöffnet werden kann.
	*
	* @param int $id - Die ID des Datenobjektes
	**/
	private function setID($id) {
		$this->id = $id;
	}
	
	/**
	* Gibt die ID zurück.
	*
	* @return int - Die ID des Datenobjektes
	**/
	public function getID() {
		return $this->id;
	}
		
	/**
	* Setzt die Gruppe eines Daten-Objekts
	*
	* @param int $group - Gruppe
	**/
	private function setGroup($group) {
		$this->group = $group;
	}
	
	/**
	* Gibt die Gruppe eines Daten-Objekts
	*
	* @param int $group - Gruppe
	**/
	public function getGroup() {
		return $this->group;
	}
	
	/**
	* Macht aus dem Objekt die ID des Datenobjekts
	*
	* @return int - ID
	**/
	public function __toString() {
		return $this->getID().': '.$this->getName();
	}
	
	/**
	* Nur die ID und vielleicht variable Einstellungen solle beim serializieren gespeichert werden.
	*
	* @return array
	**/
	public function __sleep() {
		return array('id','varProperties');
	}
	
	/**
	* Beim Aufwachen werden eventuell aktuallisierte Daten für das Objekt geladen.
	* Wenn kein Objekt mit dieser ID mehr existiert wird ein Fehler geworfen.
	**/
	public function __wakeup() {
		if(!static::existObjectForID($this->id)) throw new \Exception('Beim Aufwachen hat TrainCompany das erwartete Daten-Objekt vergessen.', 2014);
		
		$newInstance = self::getObjectForID($this->id);
		$this->name = $newInstance->getName();
		$this->group = $newInstance->getGroup();
		$this->properties = $newInstance->getProperties();
	}
}
?>