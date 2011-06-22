<?php
$head = array('title' => html_escape('Scripto'));
head($head);
?>
<h1><?php echo $head['title']; ?></h1>
<div id="primary">
<?php echo flash(); ?>

<div id="scripto-history" class="scripto">
<!-- navigation -->
<p>
<?php if ($this->scripto->isLoggedIn()): ?>
Logged in as <a href="<?php echo html_escape(uri('scripto')); ?>"><?php echo $this->scripto->getUserName(); ?></a> 
(<a href="<?php echo html_escape(uri('scripto/logout')); ?>">logout</a>) 
 | <a href="<?php echo html_escape(uri('scripto/watchlist')); ?>">Your watchlist</a> 
<?php else: ?>
<a href="<?php echo html_escape(uri('scripto/login')); ?>">Log in to Scripto</a>
<?php endif; ?>
 | <a href="<?php echo html_escape(uri('scripto/recent-changes')); ?>">Recent changes</a> 
 | <a href="<?php echo html_escape(uri(array('controller' => 'items', 'action' => 'show', 'id' => $this->doc->getId()), 'id')); ?>">View item</a>
 | <a href="<?php echo html_escape(uri(array('controller' => 'files', 'action' => 'show', 'id' => $this->doc->getPageId()), 'id')); ?>">View file</a>
 | <a href="<?php echo html_escape(uri(array('action' => 'transcribe', 'item-id' => $this->doc->getId(), 'file-id' => $this->doc->getPageId()), 'scripto_action_item_file')); ?>">Transcribe page</a>
</p>

<h2><?php if (1 == $this->namespaceIndex): ?>Discussion<?php else: ?>Transcription<?php endif; ?> Page History</h2>
<h3><?php echo $this->doc->getPageName(); ?></h3>

<!-- page history -->
<?php if (empty($this->history)): ?>
<p>This page has not yet been created.</p>
<?php else: ?>
<table>
    <thead>
    <tr>
        <th>Compare Changes</th>
        <th>Changed on</th>
        <th>Changed by</th>
        <th>Size (bytes)</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($this->history as $revision): ?>
    <?php
    $urlCurrent = uri(array('item-id' => $this->doc->getId(), 
                            'file-id' => $this->doc->getPageId(), 
                            'namespace-index' => $this->namespaceIndex, 
                            'old-revision-id' => $revision['revision_id'], 
                            'revision-id' => $this->info['last_revision_id']), 
                      'scripto_diff');
    $urlPrevious = uri(array('item-id' => $this->doc->getId(), 
                             'file-id' => $this->doc->getPageId(), 
                             'namespace-index' => $this->namespaceIndex, 
                             'old-revision-id' => $revision['parent_id'], 
                             'revision-id' => $revision['revision_id']), 
                       'scripto_diff');
    ?>
    <tr>
        <td>(<?php if ($revision['revision_id'] != $this->info['last_revision_id']): ?><a href="<?php echo html_escape($urlCurrent); ?>">current</a><?php else: ?>current<?php endif; ?> | <?php if (0 != $revision['parent_id']): ?><a href="<?php echo html_escape($urlPrevious); ?>">previous</a><?php else: ?>previous<?php endif; ?>)</td>
        <td><?php echo date('H:i:s M d, Y', strtotime($revision['timestamp'])); ?></td>
        <td><?php echo $revision['user']; ?></td>
        <td><?php echo $revision['size']; ?></td>
        <td><?php echo $revision['action']; ?></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>
</div><!-- #scripto-history -->
</div>
<?php foot(); ?>