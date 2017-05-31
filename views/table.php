<div class="row">
  <div class="col-md12">
    <div class="table-responsive">
      <table class="table table-striped table-hover" id="tableLinks">
        <thead>
          <tr class="active">
            <th class="al">#</th>
            <th class="al">Original URL</th>
            <th class="ar">Created</th>
            <th class="al">Short URL</th>
            <th class="ar">Time to Live</th>
            <th class="ar">Clicks</th>
          </tr>
        </thead>
        <tbody>
        <?php if($list):?>
          <?php foreach ($list as $value):?>
          <tr>
            <td class="al"><?=$value['id'];?></td>
            <td class="al"><a href="<?=$value['orig_url'];?>" target="_blank"><?=$value['orig_url'];?></a></td>
            <td class="ar"><?=date('j M, Y', $value['created']);?></td>
            <td class="al"><a href="<?=BASE_URL . $value['short_url'];?>" data-link="<?=$value['id'];?>" target="_blank" id="shortLinkGo"><?=BASE_URL . $value['short_url'];?></a></td>
            <td class="ar"><?=($value['ttl'] == 9999999999) ? 'permanent' : date('j M, Y', $value['ttl']);?></td>
            <td class="ar" id="click<?=$value['id'];?>" data-click="<?=$value['click'];?>"><?=$value['click'];?></td>
          </tr>
          <?php endforeach;?>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>