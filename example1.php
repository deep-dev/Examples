<?php
class Building implements ArrayAccess 
{
    protected $_container = array();

    public function offsetExists($offset)
    {
        return isset($this->_container[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->_container[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->_container[] = $value;
        } else {
            $this->_container[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->_container[$offset]);
    }

    public function save()
    {
        var_dump($this);
    }
}

$obj = new Building();
$obj['name'] = 'Main tower';
$obj['flats'] = 100;
$obj->save();

?>