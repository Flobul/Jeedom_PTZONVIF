<?php

require_once dirname(__FILE__).'/../../../core/php/core.inc.php';

include_file('core', 'authentification', 'php');

if (!isConnect('admin')) {
  throw new Exception('{{401 - Refused access}}');
}
?>
<form class="form-horizontal">
  <fieldset>
    <div class="form-group">
      <label class="col-sm-3 control-label">
        {{test}}
      </label>
      <div class="col-sm-9">
        <input class="configKey form-control" data-l1key="" />
      </div>
    </div>
  </fieldset>
</form>
