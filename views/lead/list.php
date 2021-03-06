<?php
/** 
 * @var $listLead LeadEntity[] 
 */
?>
<div class="container">
    <div class="block">
        <div class="block__body">
            <table class="table table-bordered table-hover" style="font-size: 11px;">
                <thead class="thead-dark">
                <tr>
                    <th scope="col" style="width: 115px;">Дата</th>
                    <th scope="col">ip</th>
                    <th scope="col">Useragent</th>
                    <th scope="col">Hash</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($listLead as $lead) {?>
                    <tr>
                        <td><?= date("d-m-Y H:i", $lead->created_at + 3 * 3600) ?></td>
                        <td><?= $lead->ip?></td>
                        <td><?= $lead->useragent?></td>
                        <td><?= $lead->hash?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>

        </div>
    </div>
</div> <!-- /.container -->