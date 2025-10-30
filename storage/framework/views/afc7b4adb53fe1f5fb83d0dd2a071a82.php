<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo e($room->room_number); ?> - विवरण</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <style>
        :root {
            --primary: #001F5B;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            color: var(--dark);
        }
        header {
            background: var(--primary);
            color: white;
            padding: 1.5rem 2rem;
            text-align: center;
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
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        .room-detail {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .room-header {
            padding: 1.5rem;
            background: var(--primary);
            color: white;
        }
        .room-number {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0;
        }
        .room-price {
            font-size: 1rem;
            margin: 0.3rem 0 0 0;
            opacity: 0.9;
        }
        .room-info {
            padding: 1.5rem;
        }
        .info-row {
            display: flex;
            margin-bottom: 1rem;
            border-bottom: 1px dashed #eee;
            padding-bottom: 0.8rem;
        }
        .info-label {
            width: 140px;
            font-weight: bold;
            color: var(--primary);
        }
        .info-value {
            flex: 1;
        }
        .badge {
            padding: 0.3rem 0.6rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        .available {
            background: #d4edda;
            color: #155724;
        }
        .occupied {
            background: #f8d7da;
            color: #721c24;
        }
        .maintenance {
            background: #fff3cd;
            color: #856404;
        }
        .btn-primary {
            display: inline-block;
            background: var(--primary);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 1rem;
            transition: background 0.3s;
        }
        .btn-primary:hover {
            background: #001438;
        }
        .btn-secondary {
            display: inline-block;
            background: var(--gray);
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 1rem;
            margin-left: 0.5rem;
            transition: background 0.3s;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        footer {
            text-align: center;
            padding: 2rem;
            background: var(--primary);
            color: white;
            margin-top: 3rem;
        }
        @media (max-width: 768px) {
            .info-row {
                flex-direction: column;
            }
            .info-label {
                width: auto;
                margin-bottom: 0.3rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1><i class="fas fa-door-open"></i> कोठा विवरण</h1>
    </header>

    <div class="container">
        <div class="room-detail">
            <div class="room-header">
                <h2 class="room-number">कोठा नं. <?php echo e($room->room_number); ?></h2>
                <p class="room-price">रु <?php echo e(number_format($room->price, 2)); ?>/महिना</p>
            </div>
            <div class="room-info">
                <div class="info-row">
                    <div class="info-label">कोठा प्रकार</div>
                    <div class="info-value">
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
                </div>
                <div class="info-row">
                    <div class="info-label">क्षमता</div>
                    <div class="info-value"><?php echo e($room->capacity); ?> जनासम्म बस्न सकिने</div>
                </div>
                <div class="info-row">
                    <div class="info-label">हालको भर</div>
                    <div class="info-value"><?php echo e($room->students_count); ?> जना</div>
                </div>
                <div class="info-row">
                    <div class="info-label">उपलब्ध क्षमता</div>
                    <div class="info-value"><?php echo e($room->available_capacity); ?> जना</div>
                </div>
                <div class="info-row">
                    <div class="info-label">स्थिति</div>
                    <div class="info-value">
                        <span class="badge 
                            <?php if($room->status == 'available'): ?> available
                            <?php elseif($room->status == 'occupied'): ?> occupied
                            <?php else: ?> maintenance <?php endif; ?>">
                            <?php echo e($room->status == 'available' ? 'उपलब्ध' : ($room->status == 'occupied' ? 'प्रयोगमा' : 'मर्मतको लागि')); ?>

                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">विवरण</div>
                    <div class="info-value"><?php echo e($room->description ?? 'विवरण उपलब्ध छैन।'); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">फ्लोर</div>
                    <div class="info-value"><?php echo e($room->floor ?? 'उल्लेख गरिएको छैन'); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">होस्टल</div>
                    <div class="info-value"><?php echo e($room->hostel->name ?? 'निर्धारित छैन'); ?></div>
                </div>

                <a href="#" class="btn-primary">अहिले बुक गर्नुहोस्</a>
                <a href="<?php echo e(route('public.rooms')); ?>" class="btn-secondary">पछाडि जानुहोस्</a>
            </div>
        </div>
    </div>

    <footer>
        &copy; 2025 HostelHub. सबै अधिकार सुरक्षित।
    </footer>
</body>
</html><?php /**PATH C:\laragon\www\HostelHub\resources\views\frontend\rooms\room-detail.blade.php ENDPATH**/ ?>