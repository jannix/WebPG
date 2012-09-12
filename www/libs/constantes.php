<?php
define("LIMIT_PLAYERS", 10);

define("OUT_GAME", 0);
define("IN_GAME", 1);

define("MAIN_ROOM_CHAN", 0);
define("GAME_ROOM_CHAN", 1);
define("INGAME_CHAN", 2);
define("INGAME_TEAM_CHAN", 3);

define("NO_SORT", 0x01);
define("SORT_BY_TEAM", 0x02);
define("SORT_BY_POS", 0x04);

define("UPD_LAST_TIME", 0x01);
define("UPD_LAST_MESSAGEID", 0x02);
define("UPD_ALL", UPD_LAST_TIME | UPD_LAST_MESSAGEID);

define("CHAR_TYPE_HERO", 1);
define("CHAR_TYPE_TOWER_DEFENSE", 2);
define("CHAR_TYPE_HEART", 3);

define("BLDG_TYPE_RESPAWN", 1);
define("BLDG_TYPE_SHOP", 2);

define("CFG_GAME_MAXLVL", 30);
define("CFG_GAME_TIMEPREPARE", 20);
define("CFG_GAME_MAXOBJECTS", 8);
define("CFG_GAME_MAPDISTANCE", 9);
define("CFG_GAME_AGI_BY_CASE", 17);
define("CFG_GAME_AGI_BY_HIT", 26);
define("CFG_GAME_TIME_RESPAWN_BASE", 7500);
define("CFG_GAME_TIME_RESPAWN_LVL", 1500);
define("CFG_GAME_HIT_BONUS_PTS_XP_BY_LVL", 15);
define("CFG_GAME_HIT_BONUS_PCT_XP_BY_XP", 3);
define("CFG_GAME_HIT_BONUS_PO", 10);
define("CFG_GAME_KILL_BONUS_PO", 300);
define("CFG_MAP_SIZE", 50);
?>