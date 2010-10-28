<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

class IdenticaParser {
    const STATE_FEED = 0;
    const STATE_NOTICE = 1;

    private $notices = array();

    private $limit;

    private $state = self::STATE_FEED;

    private $character_data;

    public function __construct($user, $limit=null) {
        $this->limit = $limit;

        $parser = xml_parser_create();
        xml_set_element_handler(
            $parser,
            array($this, 'start_element'),
            array($this, 'end_element')
        );
        xml_set_character_data_handler(
            $parser,
            array($this, 'character_data')
        );
        $url = "http://identi.ca/api/statuses/user_timeline/{$user}.atom";
        xml_parse($parser, file_get_contents($url), true);
        xml_parser_free($parser);
    }

    public function start_element($parser, $name, $attrs) {
        switch ($name) {
        case 'CONTENT':
            $this->state = self::STATE_NOTICE;
            $this->character_data = '';
            break;
        }
    }

    public function end_element($parser, $name)	{
        switch ($name) {
        case 'CONTENT':
            $this->notices[] = $this->character_data;
            $this->character_data = '';
            $this->state = self::STATE_FEED;
            break;
        }
    }

    public function character_data($parser, $data) {
        $this->character_data .= $data;
    }

    public function json()
    {
        return json_encode(
            !is_null($this->limit) ?
                array_slice($this->notices, 0, $this->limit) : $this->notices
        );
    }
}


function main() {
    header('Content-type: application/json');
    $user = $_GET['user'];
    $limit = $_GET['limit'];
    $parser = new IdenticaParser($user, $limit);
    echo $parser->json();
}


main();