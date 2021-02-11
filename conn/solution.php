<?php
class Collector {

    /**
     * @var array Units array
     */
    protected $blocks = [];

    /**
     * Collector constructor.
     * @param array $input
     */
    public function __construct(array $input) {
        $this->blocks = $this->convert_input_to_blocks($input);
    }

    /**
     * Convert input array into array of blocks.
     * @param array $input
     * @return array Array of bool values to determine if block exists in column or not.
     */
    protected function convert_input_to_blocks(array $input) {

        $array = [];

        // Loop through each column.
        for ($i = 0; $i < count($input); $i++) {

            // Create an array of true/false values if there is a block at each point in the column. Starting from the ground up.
            $blocks = [];
            for ($k = 0; $k < max($input); $k++) {
                $blocks[$k] = ($k < $input[$i]);
            }
            $array[$i] = $blocks;
        }

        return $array;

    }

    /**
     * Collect rain water.
     * @return int
     */
    public function collect() : int {

        $result = 0;

        // Loop through each block and see if it can contain a unit of water.
        foreach ($this->blocks as $colnum => $blocks) {
            foreach ($blocks as $blocknum => $exists) {

                // Only empty blocks can contain water.
                if (!$exists && $this->block_contains_water($colnum, $blocknum)) {
                    $result++;
                }

            }
        }

        return $result;

    }

    /**
     * Draw a table of the blocks so it's easier to understand.
     */
    public function draw() : void {

        $rows = count($this->blocks[0]) - 1;
        echo "<table style='border:1px solid #000;'>";
            for ($r = $rows; $r >= 0; $r--) {
                echo "<tr>";
                foreach ($this->blocks as $col => $blocks) {
                    echo "<td style='border:1px solid #000;' col='{$col}:{$r}'>" . (($blocks[$r]) ? 'X' : '_') . "</td>";
                }
                echo "</tr>";
            }
        echo "</table>";

    }

    /**
     * Check if a given block can contain water.
     * @param int $column
     * @param int $block
     * @return bool
     */
    protected function block_contains_water(int $column, int $block) : bool {

        // Block can only contain water if there is a solid block on either side of it.
        return ($this->has_solid_sibling_left($column, $block) && $this->has_solid_sibling_right($column, $block));

    }

    /**
     * Check if there is a solid block to the left of this block
     * @param int $column
     * @return bool
     * @throws Exception
     */
    protected function has_solid_sibling_left(int $column, int $block) : bool {

        // How many more columns do we need to check?
        // Basically we need to do the current number minus the start, which is 0. So we can just return the current number.
        // Okay so now loop back through the columns and see if any have a solid block at this level.
        for ($i = $column; $i >= 0; $i--) {
            if ($this->blocks[$i][$block]) {
                return true;
            }
        }

        return false;

    }

    /**
     * Check if there is a solid block to the right of this block
     * @param int $column
     * @return bool
     * @throws Exception
     */
    protected function has_solid_sibling_right(int $column, int $block) : bool {

        // Okay so now loop back through the columns and see if any have a solid block at this level.
        for ($i = ($column + 1); $i < count($this->blocks); $i++) {
            if ($this->blocks[$i][$block]) {
                return true;
            }
        }

        return false;

    }

}

$tests = [];
$tests[] = [4, 0, 2]; // 2
$tests[] = [3, 0, 4]; // 3
$tests[] = [4, 0, 0, 4]; // 8
$tests[] = [4, 2, 1, 4]; // 5
$tests[] = [1, 2, 0, 0]; // 0
$tests[] = [3, 0, 3, 4, 2, 5]; // 5

foreach ($tests as $test) {
    echo implode(', ', $test);
    $collector = new Collector($test);
    $collector->draw();
    echo 'Units of water: ' . $collector->collect();
    echo '<hr>';
}
