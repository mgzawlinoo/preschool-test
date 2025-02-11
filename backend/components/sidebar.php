<?php 

    $navItems = [
        ['name' => 'Dashboard', 'link'=> 'index.php', 'icon' => 'bi bi-speedometer2'],
        ['name' => 'Student', 'link'=> 'students.php', 'icon' => 'bi bi-person-square'],
        ['name' => 'Teacher', 'link'=> 'teachers.php' , 'icon' => 'bi bi-person'],
        ['name' => 'Classes', 'link'=> 'classes.php' , 'icon' => 'bi bi-calendar3'],
        ['name' => 'Payments', 'link'=> 'payments.php' , 'icon' => 'bi bi-cash'],
        ['name' => 'Notifications', 'link'=> 'notifications.php' , 'icon' => 'bi bi-bell'],
        ['name' => 'Settings', 'link'=> 'settings.php' , 'icon' => 'bi bi-gear'],
    ];

    function activeClass($itemUrl): string {
        $itemUrl = '/backend/' . $itemUrl;
        if ($_SERVER['REQUEST_URI'] == $itemUrl) {
            return 'active';
        }
        return '';
    }

?>

  <nav id="sidebar" class="bg-primary">
      <div class="sidebar-header">
          <h3 class="text-white">Little Stars</h3>
          <p class="text-white-50">Admin Dashboard</p>
      </div>

      <ul class="list-unstyled components">

            <?php foreach ($navItems as $item ): ?>
                <li class="<?php echo activeClass($item['link']); ?>">
                    <a href="<?php echo '/backend/'.$item['link'] ?>" >
                    <i class="<?php echo $item['icon'] ?>"></i> 
                    <?php echo $item['name'] ?>
                    </a>
                </li>
            <?php endforeach; ?>

      </ul>
  </nav>