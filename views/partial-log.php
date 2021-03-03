<?php

use dcms\update\includes\Database;

$db = new Database();
$rows = $db->select_table();
$pending = $db->select_table_filter();
$last_modified_file = get_option('dcms_last_modified_file',0);
?>
<style>
    table.dcms-table{
        width:100%;
        background-color:white;
        border-spacing: 0;
    }

    table.dcms-table th,
    table.dcms-table td{
        text-align:left;
        padding:6px;
        border-bottom:1px solid #ccc;
    }

    table.dcms-table tr th:first-child,
    table.dcms-table tr td:first-child{
        width:40px;
        background-color:#aaa;
    }

    table.dcms-table th,
    table.dcms-table tr th:first-child{
        background-color:#23282d;
        color:white;
    }

    table.dcms-table tr td:nth-child(2){
        font-weight:bold;
    }

    table tr.updated{
        background-color:#F4FFED;
    }

    section.msg-top{
        padding:10px;
        background-color:#ccc;
    }

    table.dcms-table th.internal{
        background-color: #757575;
    }

    table.dcms-table td.divider{
        border-left:1px solid #aaa;
    }

</style>

<section class="msg-top">
<span><?php echo DCMS_COUNT_BATCH_PROCESS . __(' Items', 'dcms-update-stock-excel') ?></span>
<span><?php echo __('every ', 'dcms-update-stock-excel') . DCMS_INTERVAL_SECONDS . "s" ?></span>
-
<strong><?php echo __('Pending items: ', 'dcms-update-stock-excel') . count($pending) ?></strong>
-
<strong><?php echo __('Last modified Excel file process: ', 'dcms-update-stock-excel') . date('d/m/Y - H:m:s', $last_modified_file) ?></strong>
</section>
<table class="dcms-table">
<?php foreach ($rows as $key => $item):  ?>
    <tr class="<?= $item->updated?'updated':'' ?>" >
    <?php if ( $key == 0 ): ?>
        <th>#</th>
        <th>SKU</th>
        <th>Product</th>
        <th>Stock</th>
        <th>Price</th>
        <th>State</th>
        <th class="internal">Updated</th>
        <th class="internal">Date updated</th>
        <th class="internal">Excluded</th>
    <?php else: ?>
        <td><?= $key; ?></td>
        <td><?= $item->sku ?></td>
        <td><?= $item->product ?></td>
        <td><?= $item->stock ?></td>
        <td><?= $item->price ?></td>
        <td><?= $item->state ?></td>
        <td class="divider"><?= $item->updated?'yes':'no' ?></td>
        <td><?= $item->date_update?date('d/m/Y - H:m:s', strtotime($item->date_update)):'' ?></td>
        <td><?= $item->excluded?'yes':'no' ?></td>
    <?php endif; ?>
    </tr>
<?php endforeach; ?>
</table>

