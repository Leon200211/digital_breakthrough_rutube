<?php

?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        <?php if($this->roleArrayIntersect(['admin', 'ceo', 'laser_machine_operator', 'laser_workshop_boss'])): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Лазерный цех</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="/laserworkshop/chart/show">
                            <i class="bi bi-circle"></i><span>Расписание</span>
                        </a>
                    </li>
                    <li>
                        <a href="/laserworkshop/shift">
                            <i class="bi bi-circle"></i><span>Смены</span>
                        </a>
                    </li>
                    <li>
                        <a href="/laserworkshop/orders">
                            <i class="bi bi-circle"></i><span>Заказы</span>
                        </a>
                    </li>
                    <?php if($this->roleArrayIntersect(['admin', 'ceo', 'laser_workshop_boss'])): ?>
                    <li class="nav-heading">Начальник цеха</li>
                    <li>
                        <a href="/laserworkshop/chart/add">
                            <i class="bi bi-circle"></i><span>Назначить смены</span>
                        </a>
                    </li>
                    <li>
                        <a href="/laserworkshop/workers">
                            <i class="bi bi-circle"></i><span>Работники</span>
                        </a>
                    </li>
                    <li>
                        <a href="/laserworkshop/reports">
                            <i class="bi bi-circle"></i><span>Отчеты</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </li><!-- End Components Nav -->
        <?php endif; ?>


        <?php if($this->roleArrayIntersect(['admin', 'ceo', 'laser_workshop_boss'])): ?>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-journal-text"></i><span>Склад</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/warehouse/sheet">
                        <i class="bi bi-circle"></i><span>Склад листов</span>
                    </a>
                </li>
                <li>
                    <a href="/warehouse/pipes" onclick="return false">
                        <i class="bi bi-circle"></i><span>Склад труб</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Forms Nav -->
        <?php endif; ?>

        <?php if($this->roleArrayIntersect(['admin', 'ceo', 'manager'])): ?>
        <li class="nav-item">
            <a class="nav-link collapsed" data-bs-target="#componentssss-nav" data-bs-toggle="collapse" href="#">
                <i class="bi bi-menu-button-wide"></i><span>Офис</span><i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <ul id="componentssss-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                <li>
                    <a href="/office/manager/orders">
                        <i class="bi bi-circle"></i><span>Заказы</span>
                    </a>
                </li>
            </ul>
        </li><!-- End Components Nav -->
        <?php endif; ?>




        <li class="nav-heading">Прочее</li>

        <li class="nav-item">
            <a class="nav-link collapsed" href="../../../index.php">
                <i class="bi bi-person"></i>
                <span>Профиль</span>
            </a>
        </li><!-- End Profile Page Nav -->


        <li class="nav-item">
            <a class="nav-link collapsed" href="../../../index.php" onclick="return false">
                <i class="bi bi-question-circle"></i>
                <span>Помощь</span>
            </a>
        </li><!-- End F.A.Q Page Nav -->

        <li class="nav-item">
            <a class="nav-link collapsed" href="../../../index.php">
                <i class="bi bi-box-arrow-in-right"></i>
                <span>Выход</span>
            </a>
        </li><!-- End Login Page Nav -->



</aside><!-- End Sidebar-->



