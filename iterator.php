<?php

class RowIterator implements SeekableIterator
{
    private $index = 0;
    private $fileHandle;
    private $rowNumber = 0;

    /**
     * RowIterator constructor.
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $this->fileHandle = fopen($fileName, "r");
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->rowNumber++;
        $this->index += strlen($this->current());
        fseek($this->fileHandle, $this->index);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        $oldIndex = $this->index;
        $return = fgets($this->fileHandle);
        $this->index = $oldIndex;
        fseek($this->fileHandle, $this->index);

        return $return . "<br>";
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->rowNumber;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return $this->current() !== false ? true : false;
    }

    /**
     * Seeks to a position
     * @link http://php.net/manual/en/seekableiterator.seek.php
     * @param int $position <p>
     * The position to seek to.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function seek($position)
    {
        if ($position === 0) {
            $this->rewind();
        } else {
            while ($position != $this->rowNumber) {
                if ($this->rowNumber < $position) {
                    $this->index += strlen(fgets($this->fileHandle));
                    $this->rowNumber++;
                } else {
                    $backward = $this->index <= 65536 ? $this->index : 65536;
                    $this->index += -$backward;
                    fseek($this->fileHandle, $this->index);
                    $string = fread($this->fileHandle, $backward);
                    fseek($this->fileHandle, $this->index);
                    $this->rowNumber += -substr_count($string, PHP_EOL) + 1;
                    $this->index += strlen(fgets($this->fileHandle));
                }
            }

            fseek($this->fileHandle, $this->index);
        }
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->index = 0;
        $this->rowNumber = 0;
        fseek($this->fileHandle, $this->index);
    }
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

$Iterator = new RowIterator("text.txt");
$timeStart = microtime_float();
for ($i = 0; $i < 10000; $i++) {
    $random = rand(0, 10000);
    $Iterator->seek($random);
}
$myTime = microtime_float() - $timeStart;

echo "Time:$myTime, random rows: 10000, from 0 to 10000";