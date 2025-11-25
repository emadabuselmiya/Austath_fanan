@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #2563eb;
            --secondary-color: #f8fafc;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --text-primary: #1f2937;
            --text-secondary: #6b7280;
            --border-color: #e5e7eb;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* RTL Support */
        * {
            direction: rtl;
            text-align: right;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif, 'Arabic UI Text', sans-serif;
            margin: 0;
            padding: 0;
        }

        .video-portal {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 1rem 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 0.5rem;
        }

        /* Header Styles */
        .portal-header {
            text-align: center;
            margin-bottom: 2rem;
            color: white;
            padding: 1rem 0;
        }

        .portal-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            line-height: 1.2;
        }

        .portal-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            font-weight: 300;
            margin-bottom: 0;
        }

        /* Stats Bar - More Responsive */
        .stats-bar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 2rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
            gap: 1rem;
            text-align: center;
        }

        .stat-item {
            color: white;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: 700;
            display: block;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.75rem;
            opacity: 0.9;
            margin-top: 0.25rem;
        }

        /* Classes Grid - Responsive */
        .classes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .class-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: var(--shadow-lg);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid var(--border-color);
        }

        .class-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .class-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 1rem;
            position: relative;
            overflow: hidden;
        }

        .class-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            transform: translate(-25px, -25px);
        }

        .class-name {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
            position: relative;
            z-index: 1;
            line-height: 1.3;
        }

        .courses-count {
            font-size: 0.75rem;
            opacity: 0.9;
            margin-top: 0.5rem;
            position: relative;
            z-index: 1;
        }

        /* Course Container - More Responsive */
        .courses-container {
            padding: 1rem;
            max-height: 500px;
            overflow-y: auto;
        }

        .courses-container::-webkit-scrollbar {
            width: 4px;
        }

        .courses-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 2px;
        }

        .courses-container::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 2px;
        }

        .course-item {
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .course-item:last-child {
            margin-bottom: 0;
        }

        .course-item:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-sm);
        }

        .course-header {
            background: var(--secondary-color);
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .course-header:hover {
            background: #f1f5f9;
        }

        .course-title {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            line-height: 1.3;
        }

        .course-icon {
            width: 1.25rem;
            height: 1.25rem;
            margin-left: 0.5rem;
            color: var(--primary-color);
            flex-shrink: 0;
        }

        .toggle-icon {
            width: 1rem;
            height: 1rem;
            color: var(--text-secondary);
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .course-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .course-content.expanded {
            max-height: 600px;
        }

        /* Subjects List - Simplified for Mobile */
        .subjects-list {
            padding: 0.75rem;
            max-height: 300px;
            overflow-y: auto;
        }

        .subjects-list::-webkit-scrollbar {
            width: 3px;
        }

        .subjects-list::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 1.5px;
        }

        .subjects-list::-webkit-scrollbar-thumb {
            background: var(--text-secondary);
            border-radius: 1.5px;
        }

        .subject-item {
            margin-bottom: 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .subject-item:last-child {
            margin-bottom: 0;
        }

        .subject-item:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-sm);
        }

        .subject-header {
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            color: var(--text-primary);
            background: rgba(59, 130, 246, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .subject-header:hover {
            background: rgba(59, 130, 246, 0.1);
        }

        .subject-title {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            line-height: 1.3;
        }

        .subject-icon {
            width: 1rem;
            height: 1rem;
            margin-left: 0.5rem;
            color: var(--primary-color);
            flex-shrink: 0;
        }

        .subject-toggle {
            width: 0.875rem;
            height: 0.875rem;
            color: var(--text-secondary);
            transition: transform 0.3s ease;
            flex-shrink: 0;
        }

        .subject-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .subject-content.expanded {
            max-height: 250px;
        }

        .lessons-count {
            font-size: 0.7rem;
            color: var(--text-secondary);
            margin-right: 0.5rem;
        }

        /* Lessons Grid - Simplified */
        .lessons-grid {
            padding: 0.5rem;
            display: grid;
            gap: 0.5rem;
            max-height: 200px;
            overflow-y: auto;
        }

        .lessons-grid::-webkit-scrollbar {
            width: 2px;
        }

        .lessons-grid::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 1px;
        }

        .lessons-grid::-webkit-scrollbar-thumb {
            background: var(--success-color);
            border-radius: 1px;
        }

        .lesson-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            padding: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
            gap: 0.5rem;
        }

        .lesson-card:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-sm);
            transform: translateX(-3px);
        }

        .lesson-info {
            display: flex;
            align-items: center;
            flex: 1;
            min-width: 0;
        }

        .lesson-icon {
            width: 1.5rem;
            height: 1.5rem;
            margin-left: 0.5rem;
            color: var(--success-color);
            background: rgba(16, 185, 129, 0.1);
            border-radius: 0.375rem;
            padding: 0.25rem;
            flex-shrink: 0;
        }

        .lesson-details h4 {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-primary);
            line-height: 1.3;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .download-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 0.375rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            text-decoration: none;
            flex-shrink: 0;
            white-space: nowrap;
        }

        .download-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
            color: white;
            text-decoration: none;
        }

        .download-icon {
            width: 0.875rem;
            height: 0.875rem;
            margin-left: 0.375rem;
        }

        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-secondary);
        }

        .empty-icon {
            width: 3rem;
            height: 3rem;
            margin: 0 auto 0.75rem;
            color: var(--text-secondary);
        }

        .loading-spinner {
            display: inline-block;
            width: 0.875rem;
            height: 0.875rem;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Footer */
        .portal-footer {
            text-align: center;
            padding: 2rem 1rem;
            color: rgba(255, 255, 255, 0.8);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: 2rem;
        }

        .footer-text {
            font-size: 0.875rem;
            margin: 0;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .video-portal {
                padding: 0.5rem 0;
            }

            .container {
                padding: 0 0.75rem;
            }

            .portal-header {
                margin-bottom: 1.5rem;
                padding: 0.75rem 0;
            }

            .portal-title {
                font-size: 1.75rem;
            }

            .portal-subtitle {
                font-size: 0.875rem;
            }

            .stats-bar {
                padding: 0.75rem;
                margin-bottom: 1.5rem;
                grid-template-columns: repeat(2, 1fr);
                gap: 0.75rem;
            }

            .stat-number {
                font-size: 1.25rem;
            }

            .stat-label {
                font-size: 0.7rem;
            }

            .classes-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .class-header {
                padding: 0.75rem;
            }

            .class-name {
                font-size: 1.125rem;
            }

            .courses-container {
                padding: 0.75rem;
                max-height: 400px;
            }

            .course-header {
                padding: 0.5rem 0.75rem;
            }

            .course-title {
                font-size: 0.9rem;
            }

            .subjects-list {
                padding: 0.5rem;
                max-height: 250px;
            }

            .subject-header {
                padding: 0.5rem;
            }

            .subject-title {
                font-size: 0.825rem;
            }

            .lessons-grid {
                padding: 0.375rem;
                max-height: 150px;
            }

            .lesson-card {
                padding: 0.5rem;
                flex-direction: row;
                align-items: center;
            }

            .lesson-details h4 {
                font-size: 0.8rem;
            }

            .download-btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.7rem;
            }

            .download-icon {
                width: 0.75rem;
                height: 0.75rem;
                margin-left: 0.25rem;
            }

            .portal-footer {
                padding: 1.5rem 1rem;
            }

            .footer-text {
                font-size: 0.8rem;
            }
        }

        /* Extra Small Screens */
        @media (max-width: 480px) {
            .container {
                padding: 0 0.5rem;
            }

            .portal-title {
                font-size: 1.5rem;
            }

            .portal-subtitle {
                font-size: 0.8rem;
            }

            .stats-bar {
                padding: 0.5rem;
                grid-template-columns: repeat(4, 1fr);
                gap: 0.5rem;
            }

            .stat-number {
                font-size: 1.125rem;
            }

            .stat-label {
                font-size: 0.65rem;
            }

            .class-header {
                padding: 0.5rem;
            }

            .class-name {
                font-size: 1rem;
            }

            .courses-count {
                font-size: 0.7rem;
            }

            .courses-container {
                padding: 0.5rem;
            }

            .lesson-card {
                padding: 0.375rem;
            }

            .lesson-details h4 {
                font-size: 0.75rem;
            }

            .download-btn {
                padding: 0.25rem 0.375rem;
                font-size: 0.65rem;
            }
        }
    </style>

    <div class="video-portal">
        <div class="container">
            <!-- Header -->
            <div class="portal-header">
                <h1 class="portal-title">بوابة الفيديوهات التعليمية</h1>
                <p class="portal-subtitle">الوصول إلى المواد الدراسية والفيديوهات التعليمية</p>
            </div>

            <!-- Stats Bar -->
            <div class="stats-bar">
                <div class="stat-item">
                    <span class="stat-number">{{ $courses->groupBy('class_id')->count() }}</span>
                    <span class="stat-label">الصفوف</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $courses->count() }}</span>
                    <span class="stat-label">المواد</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $courses->sum(function ($course) { return $course->subjects->count(); }) }}</span>
                    <span class="stat-label">الوحدات</span>
                </div>
                <div class="stat-item">
                    <span class="stat-number">{{ $courses->sum(function ($course) { return $course->subjects->sum(function ($subject) { return $subject->lessons->count(); }); }) }}</span>
                    <span class="stat-label">الدروس</span>
                </div>
            </div>

            <!-- Classes Grid -->
            <div class="classes-grid">
                @forelse($courses->groupBy('class_id') as $classId => $classCourses)
                    <div class="class-card">
                        <div class="class-header">
                            <h2 class="class-name">
                                {{ $classCourses->first()->type ?? 'صف غير محدد' }}
                            </h2>
                            <p class="courses-count">
                                {{ $classCourses->count() }} 
                                @if($classCourses->count() == 1)
                                    مادة
                                @elseif($classCourses->count() == 2)
                                    مادتان
                                @elseif($classCourses->count() <= 10)
                                    مواد
                                @else
                                    مادة
                                @endif
                            </p>
                        </div>

                        <div class="courses-container">
                            @foreach($classCourses as $course)
                                <div class="course-item">
                                    <div class="course-header" onclick="toggleCourse('course-{{ $course->id }}')">
                                        <h3 class="course-title">
                                            <svg class="course-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z">
                                                </path>
                                            </svg>
                                            {{ $course->name }}
                                        </h3>
                                        <svg class="toggle-icon" id="toggle-course-{{ $course->id }}" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>

                                    <div class="course-content" id="course-{{ $course->id }}">
                                        <div class="subjects-list">
                                            @forelse($course->subjects as $subject)
                                                <div class="subject-item">
                                                    <div class="subject-header" onclick="toggleSubject('subject-{{ $subject->id }}')">
                                                        <div class="subject-title">
                                                            <svg class="subject-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                                </path>
                                                            </svg>
                                                            {{ $subject->name }}
                                                            <span class="lessons-count">
                                                                ({{ $subject->lessons->count() }} 
                                                                @if($subject->lessons->count() == 1)
                                                                    درس
                                                                @elseif($subject->lessons->count() == 2)
                                                                    درسان
                                                                @elseif($subject->lessons->count() <= 10)
                                                                    دروس
                                                                @else
                                                                    درس
                                                                @endif)
                                                            </span>
                                                        </div>
                                                        <svg class="subject-toggle" id="toggle-subject-{{ $subject->id }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </div>

                                                    <div class="subject-content" id="subject-{{ $subject->id }}">
                                                        <div class="lessons-grid">
                                                            @forelse($subject->lessons as $lesson)
                                                                <div class="lesson-card">
                                                                    <div class="lesson-info">
                                                                        <svg class="lesson-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M19 10a9 9 0 11-18 0 9 9 0 0118 0z">
                                                                            </path>
                                                                        </svg>
                                                                        <div class="lesson-details">
                                                                            <h4>{{ $lesson->name }}</h4>
                                                                        </div>
                                                                    </div>

                                                                    @if($lesson->video)
                                                                        <a href="{{ route('download.video', $lesson->id) }}" class="download-btn">
                                                                            <svg class="download-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                                </path>
                                                                            </svg>
                                                                            تحميل
                                                                        </a>
                                                                    @else
                                                                        <span class="download-btn" style="background: #9ca3af; cursor: not-allowed;">
                                                                            <svg class="download-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                                    d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728">
                                                                                </path>
                                                                            </svg>
                                                                            لا يوجد فيديو
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            @empty
                                                                <div class="empty-state">
                                                                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                                        </path>
                                                                    </svg>
                                                                    <p>لا توجد دروس متاحة</p>
                                                                </div>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="empty-state">
                                                    <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                                        </path>
                                                    </svg>
                                                    <p>لا توجد وحدات متاحة</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1; background: white; border-radius: 1rem; box-shadow: var(--shadow-lg);">
                        <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253z">
            </path>
                        </svg>
                        <h3>لا توجد مواد متاحة</h3>
                        <p>لا توجد مواد دراسية متاحة في الوقت الحالي. يرجى المحاولة مرة أخرى لاحقاً.</p>
                    </div>
                @endforelse
            </div>

            <!-- Footer -->
            <div class="portal-footer">
                <p class="footer-text">جميع الحقوق محفوظة © 2025 استاذ فنان</p>
            </div>
        </div>
    </div>

    <script>
        function toggleCourse(courseId) {
            const content = document.getElementById(courseId);
            const icon = document.getElementById('toggle-' + courseId);

            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                icon.style.transform = 'rotate(0deg)';
            } else {
                content.classList.add('expanded');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        function toggleSubject(subjectId) {
            const content = document.getElementById(subjectId);
            const icon = document.getElementById('toggle-' + subjectId);

            if (content.classList.contains('expanded')) {
                content.classList.remove('expanded');
                icon.style.transform = 'rotate(0deg)';
            } else {
                content.classList.add('expanded');
                icon.style.transform = 'rotate(180deg)';
            }
        }

        // Add loading states to download buttons
        document.querySelectorAll('.download-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                if (this.getAttribute('href')) {
                    const originalText = this.innerHTML;
                    this.innerHTML = '<div class="loading-spinner"></div> جاري التحميل...';
                    this.style.pointerEvents = 'none';

                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.style.pointerEvents = 'auto';
                    }, 3000);
                }
            });
        });
    </script>
@endsection