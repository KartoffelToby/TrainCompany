<?php function escapeMySQL() { return call_user_func_array(\Core\Alias::getCallbackForAlias('escapeMySQL'), func_get_args()); } ?>