<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Shape Entity
 *
 * @property string $shape_id
 * @property string $shape_name
 * @property string $shape_polyline
 *
 * @property \App\Model\Entity\Trip[] $trips
 */
class Shape extends Entity
{
    /**
     * @var Client Single instance for permission checks.
     */
    protected static $_singleInstance;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'shape_id' => true,
        'shape_name' => true,
        'shape_polyline' => true,
        'trips' => true
    ];

    protected $_hidden = [
        'shape_name',
        'shape_polyline',
        'flags',
        'created',
        'modified'
    ];

    protected $_virtual = [
        'points'
    ];

    /**
     * This member is used to store a value for points-property from outer-class.
     * Otherwise the content of the property will always be re-calculated by the magic
     * getter method.
     *
     * @var array|null
     */
    protected $_bufferedPoints = null;

    /**
     * Returns a single instance Client object for permission checks.
     *
     * @return Client Single instance object for permission checks.
     */
    public static function getInstance()
    {
        if (self::$_singleInstance == null) {
            self::$_singleInstance = new Shape();
        }

        return self::$_singleInstance;
    }

    /**
     * Virtual field with decoded shape coordinates.
     *
     * @return array The decoded shape coordinates array.
     */
    protected function _getPoints()
    {
        // if there are buffered points set from somewhere else, use this
        if($this->_bufferedPoints != null) {
            return $this->_bufferedPoints;
        }

        // if not set, decode the polyline and return this points
        return $this->decode($this->shape_polyline);
    }

    /**
     * Overrides the content of the points-property from somewhere out
     * of this class.
     *
     * @param $points The point array to use in future.
     */
    protected function _setPoints($points)
    {
        $this->_bufferedPoints = $points;
    }

    /**
     * Encodes an array of coordinates.
     *
     * @param array $points The input array with coordinates.
     * @return string Polyline-encoded coordinates array.
     */
    public function encode(array $points)
    {
        $precision = 5;
        $tuple = 2;

        // Flatten given points
        $tmp = array();
        foreach($points as $point) {
            array_push($tmp, $point['lat']);
            array_push($tmp, $point['lon']);
        }
        $points = $tmp;

        $previous = array_fill(0,$tuple,0);
        $encoded_string = '';
        $index = 0;
        foreach($points as $number) {
            $number = (float)($number);
            $number = (int)round($number * pow(10, $precision));
            $diff = $number - $previous[$index % $tuple];
            $previous[$index % $tuple] = $number;
            $number = $diff;
            $index++;
            $number = ($number < 0) ? ~($number << 1) : ($number << 1);
            $chunk = '';
            while($number >= 0x20) {
                $chunk .= chr((0x20 | ($number & 0x1f)) + 63);
                $number >>= 5;
            }
            $chunk .= chr($number + 63);
            $encoded_string .= $chunk;
        }

        return $encoded_string;
    }

    /**
     * Decodes a polyline string to an array of coordinates.
     *
     * @param $string The polyline-encoded input coordinates.
     * @return array The decoded coordinates array.
     */
    public static function decode($string) {
        $precision = 5;
        $tuple = 2;

        $points = array();
        $index = $i = 0;
        $previous = array_fill(0,$tuple,0);
        while( $i < strlen($string)  ) {
            $shift = $result = 0x00;
            do {
                $bit = ord(substr($string,$i++)) - 63;
                $result |= ($bit & 0x1f) << $shift;
                $shift += 5;
            } while( $bit >= 0x20 );
            $diff = ($result & 1) ? ~($result >> 1) : ($result >> 1);
            $number = $previous[$index % $tuple] + $diff;
            $previous[$index % $tuple] = $number;
            $index++;
            $points[] = $number * 1 / pow(10, $precision);
        }

        if($tuple > 1)
        {
            $points = array_chunk($points, $tuple);
        }

        for($p = 0; $p < count($points); $p++) {
            $tmp = ['lat' => $points[$p][0], 'lon' => $points[$p][1]];
            $points[$p] = $tmp;
        }

        return $points;
    }
}
