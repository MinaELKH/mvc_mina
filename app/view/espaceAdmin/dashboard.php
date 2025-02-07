<?php

ob_start();

require_once $_SERVER['DOCUMENT_ROOT'] . '/youdemy/autoloader.php';

use classes\DashboardStats;
use config\DataBaseManager;

$dbManager = new DataBaseManager();
$stats = new DashboardStats($dbManager);
$topTeachers = $stats->getTopTeachers();
$topCategories = $stats->getTopCategories();
$topCourses = $stats->getTopCourses();
$teachersEnrol= $stats->getTopTeachersEnrol();
$pendingStats = $stats->getPendingCourse_Teacher();
?>

<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-light text-gray-800">Tableau de bord</h1>
        <div class="text-sm text-gray-500">
            Dernière mise à jour : <?= date('d/m/Y H:i') ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white border rounded-lg p-4 text-center">
            <p class="text-gray-500 mb-2">Cours en attente</p>
            <h3 class="text-3xl font-bold text-gray-800"><?= $pendingStats['courses'] ?></h3>
        </div>

        <div class="bg-white border rounded-lg p-4 text-center">
            <p class="text-gray-500 mb-2">Enseignants en attente</p>
            <h3 class="text-3xl font-bold text-gray-800"><?= $pendingStats['teachers'] ?></h3>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Top Professeurs -->
        <div class="bg-white border rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Top Professeurs Actifs</h2>
            <?php foreach ($topTeachers as $index => $teacher): ?>
                <div class="flex justify-between mb-2 pb-2 border-b last:border-b-0">
                    <span class="text-gray-700"><?= htmlspecialchars($teacher['name_full']) ?></span>
                    <span class="text-gray-500"><?= $teacher['nbCourse'] ?> cours</span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Catégories populaires -->
        <div class="bg-white border rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Catégories demandées</h2>
            <?php foreach ($topCategories as $index => $category): ?>
                <div class="flex justify-between mb-2 pb-2 border-b last:border-b-0">
                    <span class="text-gray-700"><?= htmlspecialchars($category['NAME']) ?></span>
                    <span class="text-gray-500"><?= $category['nbCourse'] ?> cours</span>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Cours populaires -->
        <div class="bg-white border rounded-lg p-4">
            <h2 class="text-lg font-semibold text-gray-700 mb-4">Cours populaires</h2>
            <?php foreach ($topCourses as $index => $course): ?>
                <div class="flex justify-between mb-2 pb-2 border-b last:border-b-0">
                    <span class="text-gray-700"><?= htmlspecialchars($course['title']) ?></span>
                    <span class="text-gray-500"><?= $course['inscrits'] ?> inscrits</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Professeurs par inscriptions -->
    <div class="mt-8 bg-white border rounded-lg p-4">
        <h2 class="text-lg font-semibold text-gray-700 mb-4">Professeurs par inscriptions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <?php foreach ($teachersEnrol as $teacher): ?>
                <div class="border-b pb-2">
                    <div class="flex justify-between">
                        <span class="text-gray-700"><?= htmlspecialchars($teacher['name_full']) ?></span>
                        <span class="text-gray-500"><?= $teacher['nb'] ?> inscrits</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<?php
$content = ob_get_clean();
include('layout.php');
?>