<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>उपलब्ध कोठाहरू - HostelHub</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        :root {
            --primary: #001F5B;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --success: #28a745;
            --border: #ddd;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            color: var(--dark);
            line-height: 1.6;
        }
        header {
            background: var(--primary);
            color: white;
            padding: 1.5rem 2rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        header h1 {
            font-size: 1.8rem;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-bottom: 2rem;
            background: white;
            padding: 1rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .search-input {
            flex: 1;
            min-width: 200px;
            padding: 0.7rem;
            border: 1px solid var(--border);
            border-radius: 50px;
            font-size: 0.9rem;
        }
        .filter-btn {
            padding: 0.6rem 1rem;
            border: 1px solid var(--border);
            background: white;
            border-radius: 50px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }
        .filter-btn:hover {
            background: #e9ecef;
        }
        .filter-btn.active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .room-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        .room-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .room-card:hover {
            transform: translateY(-5px);
        }
        .room-header {
            padding: 1rem;
            background: var(--primary);
            color: white;
        }
        .room-number {
            font-size: 1.3rem;
            font-weight: bold;
            margin: 0;
        }
        .room-price {
            font-size: 0.95rem;
            margin: 0;
            opacity: 0.9;
        }
        .room-info {
            padding: 1.2rem;
        }
        .room-type {
            font-weight: bold;
            color: var(--primary);
            margin-bottom: 0.3rem;
        }
        .room-capacity {
            color: var(--gray);
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .room-students {
            font-size: 0.85rem;
            color: #007bff;
            margin-bottom: 0.8rem;
        }
        .room-description {
            color: var(--dark);
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }
        .btn-book {
            display: inline-block;
            width: 100%;
            text-align: center;
            background: var(--primary);
            color: white;
            padding: 0.7rem 0;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        .btn-book:hover {
            background: #001438;
        }
        .pagination {
            margin-top: 3rem;
            text-align: center;
        }
        .pagination a {
            display: inline-block;
            padding: 0.5rem 0.8rem;
            margin: 0 0.2rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            color: var(--dark);
            text-decoration: none;
        }
        .pagination a:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .pagination .active {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        footer {
            text-align: center;
            padding: 2rem;
            background: var(--primary);
            color: white;
            margin-top: 4rem;
        }
        @media (max-width: 768px) {
            .filter-bar {
                flex-direction: column;
            }
            .room-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1><i class="fas fa-bed"></i> हाम्रा उपलब्ध कोठाहरू</h1>
    </header>

    <div class="container">
        <!-- Filter Bar -->
        <div class="filter-bar">
            <input type="text" placeholder="कोठा खोज्नुहोस्..." class="search-input" />

            <a href="?type=single" class="filter-btn <?php echo e(request('type') == 'single' ? 'active' : ''); ?>">१ सिटर कोठा</a>
            <a href="?type=double" class="filter-btn <?php echo e(request('type') == 'double' ? 'active' : ''); ?>">२ सिटर कोठा</a>
            <a href="?type=dorm" class="filter-btn <?php echo e(request('type') == 'dorm' ? 'active' : ''); ?>">४ सिटर कोठा</a>
            <a href="?" class="filter-btn">सबै</a>
        </div>

        <!-- Room Grid -->
        <div class="room-grid">
            <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="room-card">
                <div class="room-header">
                    <h3 class="room-number">कोठा नं. <?php echo e($room->room_number); ?></h3>
                    <p class="room-price">रु <?php echo e(number_format($room->price, 2)); ?>/महिना</p>
                </div>
                <div class="room-info">
                    <div class="room-type">
                        <?php if($room->type == 'single'): ?>
                            १ सिटर कोठा
                        <?php elseif($room->type == 'double'): ?>
                            २ सिटर कोठा
                        <?php elseif($room->type == 'dorm'): ?>
                            ४ सिटर कोठा
                        <?php else: ?>
                            <?php echo e($room->type); ?>

                        <?php endif; ?>
                    </div>
                    <p class="room-capacity"><?php echo e($room->capacity); ?> जनासम्म बस्न सकिने</p>
                    <p class="room-students"><?php echo e($room->students_count); ?>/<?php echo e($room->capacity); ?> भरिएको</p>
                    <p class="room-description"><?php echo e($room->description ?? 'विवरण उपलब्ध छैन।'); ?></p>
                    <a href="<?php echo e(route('public.rooms.show', $room->id)); ?>" class="btn-book">विवरण हेर्नुहोस्</a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php echo e($rooms->appends(request()->query())->links()); ?>

        </div>
    </div>

    <footer>
        &copy; 2025 HostelHub. सबै अधिकार सुरक्षित।
    </footer>
</body>
</html><?php /**PATH D:\My Projects\HostelHub\resources\views\frontend\rooms\rooms.blade.php ENDPATH**/ ?>