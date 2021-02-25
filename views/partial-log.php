<?php

use dcms\update\includes\Database;

$db = new Database();
$rows = $db->select_table();
$pending = $db->select_table_filter();

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

</style>

<section class="msg-top">
<span><?php echo DCMS_COUNT_BATCH_PROCESS . __(' Items', 'dcms-update-stock-excel') ?></span>
<span><?php echo __('every ', 'dcms-update-stock-excel') . DCMS_INTERVAL_SECONDS . "s" ?></span>
-
<strong><?php echo __('Pending items: ', 'dcms-update-stock-excel') . count($pending) ?></strong>
</section>
<table class="dcms-table">
<?php foreach ($rows as $key => $item):  ?>
    <tr class="<?= $item->updated?'updated':'' ?>" >
    <?php if ( $key == 0 ): ?>
        <th>#</th>
        <th>SKU</th>
        <th>Stock</th>
        <th>Price</th>
        <th>Updated</th>
        <th>Modified</th>
        <th>Excluded</th>
    <?php else: ?>
        <td><?= $key; ?></td>
        <td><?= $item->sku ?></td>
        <td><?= $item->stock ?></td>
        <td><?= $item->price ?></td>
        <td><?= $item->updated ?></td>
        <td><?= $item->date_update ?></td>
        <td><?= $item->excluded ?></td>
    <?php endif; ?>
    </tr>
<?php endforeach; ?>
</table>

