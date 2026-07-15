<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\EducationController;
use App\Http\Controllers\LabScanController;
use App\Http\Controllers\GeneticTreeController;
use App\Http\Controllers\GeoTriageController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\ActionPlanController;
use App\Http\Controllers\ReferralCardController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\ReadinessScoreController;
use App\Http\Controllers\CostSimulatorController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\EpidemiologiController;
use App\Http\Controllers\CatinProfileController;
use App\Http\Controllers\VitaminDetectionController;
use App\Http\Controllers\VitaminScanController;

// Chatbot (Fitur 1)
Route::post('/chat', [ChatController::class, 'chat']);

// Education / Literasi (Fitur 1)
Route::get('/education', [EducationController::class, 'index']);
Route::get('/education/{id}', [EducationController::class, 'show']);
Route::post('/education/quiz', [EducationController::class, 'submitQuiz']);

// Lab Scanner (Fitur 2)
Route::post('/lab-scan/analyze', [LabScanController::class, 'analyze']);

// Genetic Tree (Fitur 3)
Route::post('/genetic-tree/calculate', [GeneticTreeController::class, 'calculate']);

// Geo-Triage / Map (Fitur 4)
Route::get('/geo-triage/provinces', [GeoTriageController::class, 'provinces']);
Route::get('/geo-triage/faskes', [GeoTriageController::class, 'faskes']);
Route::post('/geo-triage/update-stock', [GeoTriageController::class, 'updateStock']);

// 72-Hour Tracker (Fitur 5)
Route::get('/tracker', [TrackerController::class, 'index']);
Route::post('/tracker/start', [TrackerController::class, 'start']);
Route::get('/tracker/{id}', [TrackerController::class, 'show']);
Route::post('/tracker/{id}/complete', [TrackerController::class, 'complete']);

// Action Plan (Fitur 6)
Route::post('/action-plan/generate', [ActionPlanController::class, 'generate']);
Route::post('/action-plan/{id}/approve', [ActionPlanController::class, 'approve']);

// Referral Card (Fitur 7)
Route::post('/referral/generate', [ReferralCardController::class, 'generate']);
Route::get('/referral/{id}', [ReferralCardController::class, 'show']);
Route::get('/referral/verify/{kode}', [ReferralCardController::class, 'verify']);

// Donor Network (Fitur 8)
Route::get('/donors', [DonorController::class, 'index']);
Route::post('/donors/register', [DonorController::class, 'register']);

// Readiness Score (Fitur 9)
Route::get('/readiness-score/{catinId}', [ReadinessScoreController::class, 'show']);

// Cost Simulator (Fitur 11)
Route::post('/cost-simulate', [CostSimulatorController::class, 'simulate']);

// Certificate (Fitur 12)
Route::get('/certificate/{catinId}', [CertificateController::class, 'show']);

// Epidemiologi Dashboard (Fitur 14)
Route::get('/epidemiologi/stats', [EpidemiologiController::class, 'stats']);
Route::get('/epidemiologi/dashboard', [EpidemiologiController::class, 'dashboard']);

// Catin Profile CRUD
Route::post('/catin-profile', [CatinProfileController::class, 'store']);
Route::get('/catin-profile/{id}', [CatinProfileController::class, 'show']);
Route::put('/catin-profile/{id}', [CatinProfileController::class, 'update']);

// ============================================================
// Deteksi Vitamin & Gizi Anak
// ============================================================
Route::post('/vitamin-detection/analyze-photo', [VitaminDetectionController::class, 'analyzePhoto']);
Route::post('/vitamin-detection/submit',         [VitaminDetectionController::class, 'submit']);
Route::get('/vitamin-detection',                 [VitaminDetectionController::class, 'index']);
Route::get('/vitamin-detection/{id}',            [VitaminDetectionController::class, 'show']);

// ============================================================
// Scan & Deep Learning Deteksi Gizi/Vitamin Anak
// ============================================================
Route::post('/vitamin-scan/analyze-single',       [VitaminScanController::class, 'analyzeSingle']);
Route::post('/vitamin-scan/analyze-full',         [VitaminScanController::class, 'analyzeFull']);
Route::get('/vitamin-scan/history/{childName}',   [VitaminScanController::class, 'history']);
Route::post('/vitamin-scan/{id}/compare',         [VitaminScanController::class, 'compare']);
Route::get('/vitamin-scan/{id}',                  [VitaminScanController::class, 'show']);

// ============================================================
// GiziKu — AI Nutrition Screening
// ============================================================
use App\Http\Controllers\NutriScanController;
Route::post('/nutriscan/analyze', [NutriScanController::class, 'analyze']);