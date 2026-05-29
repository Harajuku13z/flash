<?php
function imgPath($base) {
    foreach(['jpg','jpeg','png','webp','gif','svg'] as $ext) {
        if(file_exists(__DIR__.'/assets/images/'.$base.'.'.$ext))
            return '/assets/images/'.$base.'.'.$ext;
    }
    return null;
}

require_once __DIR__ . '/includes/db.php';
$pdo = getDB();
$offers   = [];
$drivers  = [];
$articles = [];
$offersCount  = 0;
$driversCount = 0;
if ($pdo) {
    try {
        $offers       = $pdo->query("SELECT * FROM offers WHERE status='active' ORDER BY is_urgent DESC, created_at DESC LIMIT 6")->fetchAll();
        $drivers      = $pdo->query("SELECT * FROM drivers WHERE status='active' LIMIT 6")->fetchAll();
        $articles     = $pdo->query("SELECT * FROM articles WHERE status='published' ORDER BY created_at DESC LIMIT 3")->fetchAll();
        $offersCount  = (int)$pdo->query("SELECT COUNT(*) FROM offers WHERE status='active'")->fetchColumn();
        $driversCount = (int)$pdo->query("SELECT COUNT(*) FROM drivers WHERE status='active'")->fetchColumn();
    } catch (Exception $e) {}
}
$displayOffers  = $offersCount  > 0 ? $offersCount  : 12;
$displayDrivers = $driversCount > 0 ? $driversCount : 48;
$pageTitle = 'FLASH — Plateforme taxi Congo | Offres chauffeur Brazzaville & Pointe-Noire';
include __DIR__ . '/includes/header.php';
?>

<style>
/* ── Reset & base ─────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; }

/* ── Hero full-bleed ──────────────────────────── */
.hw-hero {
    position: relative;
    width: 100%;
    height: 420px;
    overflow: hidden;
    background: #1a0a05;
}
.hw-hero-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
}
.hw-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.15) 0%, rgba(0,0,0,0.05) 40%, rgba(0,0,0,0.45) 100%);
}
.hw-hero-text {
    position: absolute;
    right: 8%;
    top: 50%;
    transform: translateY(-50%);
    max-width: 520px;
    text-align: right;
}
.hw-hero-text h1 {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(26px, 3.5vw, 48px);
    font-weight: 400;
    color: #fff;
    line-height: 1.2;
    margin: 0;
    text-shadow: 0 2px 20px rgba(0,0,0,0.5);
}
.hw-hero-text h1 strong {
    font-weight: 900;
    display: inline;
}
.hw-hero-text h1 .hw-count {
    font-weight: 900;
}

/* ── Search bar flottante ─────────────────────── */
.hw-search-wrap {
    max-width: 900px;
    margin: -28px auto 0;
    padding: 0 24px;
    position: relative;
    z-index: 10;
}
.hw-search-bar {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 40px rgba(0,0,0,0.18);
    display: grid;
    grid-template-columns: 1fr 1fr auto;
    overflow: hidden;
    border: 1px solid #E5E7EB;
}
.hw-search-field {
    padding: 18px 24px;
    border: none;
    outline: none;
    cursor: pointer;
    transition: background 0.15s;
}
.hw-search-field:hover { background: #F9FAFB; }
.hw-search-field + .hw-search-field {
    border-left: 1px solid #E5E7EB;
}
.hw-search-field label {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-size: 11px;
    font-weight: 800;
    color: #111;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.hw-search-field input,
.hw-search-field select {
    width: 100%;
    border: none;
    outline: none;
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: #6B7280;
    background: transparent;
    cursor: pointer;
}
.hw-search-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #111;
    width: 72px;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
    flex-shrink: 0;
}
.hw-search-btn:hover { background: #333; }
.hw-search-btn svg { color: #fff; }

/* ── Filter pills ─────────────────────────────── */
.hw-pills {
    max-width: 900px;
    margin: 16px auto 0;
    padding: 0 24px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.hw-pill {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    padding: 9px 18px;
    border: 1.5px solid #E5E7EB;
    border-radius: 50px;
    background: #fff;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 500;
    color: #111;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.2s;
}
.hw-pill:hover {
    border-color: #111;
    background: #F9FAFB;
}
.hw-pill-icon { font-size: 15px; }

/* ── Banner IA ────────────────────────────────── */
.hw-banner {
    max-width: 900px;
    margin: 16px auto 0;
    padding: 0 24px;
}
.hw-banner-inner {
    background: #fff;
    border: 1.5px solid #E5E7EB;
    border-radius: 14px;
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 14px 20px;
}
.hw-banner-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366F1, #8B5CF6);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
    position: relative;
}
.hw-banner-avatar::after {
    content: '✦';
    position: absolute;
    top: -4px;
    right: -4px;
    font-size: 12px;
    color: #6366F1;
    background: #fff;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.hw-banner-text { flex: 1; }
.hw-banner-text strong {
    display: block;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 700;
    color: #6366F1;
    margin-bottom: 2px;
}
.hw-banner-text span {
    font-size: 13px;
    color: #6B7280;
}
.hw-banner-cta {
    background: #6366F1;
    color: #fff;
    border-radius: 50px;
    padding: 10px 22px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.2s;
    font-family: 'Montserrat', sans-serif;
}
.hw-banner-cta:hover { background: #4F46E5; }

/* ── Section wrapper ──────────────────────────── */
.hw-section {
    max-width: 1200px;
    margin: 0 auto;
    padding: 64px 24px;
}
.hw-section-sm { padding: 48px 24px; }

/* ── Section title style Hello Work ──────────── */
.hw-title {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(26px, 3.5vw, 42px);
    font-weight: 400;
    color: #111;
    line-height: 1.15;
    margin: 0 0 36px;
}
.hw-title strong { font-weight: 900; display: block; }

/* ── Carousel des propriétaires ──────────────── */
.hw-carousel-wrap { position: relative; }
.hw-carousel {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    overflow: hidden;
}
.hw-owner-card {
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid #E5E7EB;
    background: #fff;
    cursor: pointer;
    text-decoration: none;
    display: block;
    transition: box-shadow 0.2s, transform 0.2s;
}
.hw-owner-card:hover {
    box-shadow: 0 8px 32px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}
.hw-owner-photo {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    background: #F3F4F6;
}
.hw-owner-photo-placeholder {
    width: 100%;
    height: 200px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 70px;
}
.hw-owner-info {
    padding: 16px 20px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.hw-owner-info-left {}
.hw-owner-logo {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    background: #F9FAFB;
    border: 1px solid #E5E7EB;
}
.hw-owner-name {
    font-family: 'Montserrat', sans-serif;
    font-size: 17px;
    font-weight: 700;
    color: #111;
    margin-bottom: 4px;
}
.hw-owner-count {
    font-size: 14px;
    color: #6B7280;
}
.hw-owner-arrow {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #111;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: background 0.2s;
}
.hw-owner-card:hover .hw-owner-arrow { background: #0070FF; }
.hw-owner-arrow svg { color: #fff; }

/* ── Carousel nav ─────────────────────────────── */
.hw-carousel-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 28px;
}
.hw-carousel-dots {
    display: flex;
    gap: 8px;
    align-items: center;
}
.hw-dot {
    width: 32px;
    height: 6px;
    border-radius: 3px;
    background: #111;
    transition: background 0.2s;
    cursor: pointer;
}
.hw-dot.inactive {
    background: #E5E7EB;
    width: 6px;
}
.hw-carousel-arrows {
    display: flex;
    gap: 8px;
}
.hw-arrow-btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 1.5px solid #E5E7EB;
    background: #fff;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    color: #111;
}
.hw-arrow-btn:hover {
    border-color: #111;
    background: #F9FAFB;
}

/* ── Btn Noir pill "Voir toutes" ──────────────── */
.hw-btn-black {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #111;
    color: #fff;
    border-radius: 50px;
    padding: 14px 28px;
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    transition: background 0.2s;
    margin-top: 24px;
}
.hw-btn-black:hover { background: #333; }

/* ── Feature 2-col ────────────────────────────── */
.hw-feature-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    align-items: start;
}
.hw-feature-big {
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    min-height: 420px;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    text-decoration: none;
    transition: transform 0.3s;
}
.hw-feature-big:hover { transform: scale(1.01); }
.hw-feature-big-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hw-feature-big-placeholder {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 100px;
    opacity: 0.25;
}
.hw-feature-big-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.1) 60%);
}
.hw-feature-big-content {
    position: relative;
    z-index: 2;
    padding: 32px;
}
.hw-feature-big-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff;
    border-radius: 50px;
    padding: 5px 14px;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 12px;
    font-family: 'Montserrat', sans-serif;
}
.hw-feature-big-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 22px;
    font-weight: 800;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 16px;
}
.hw-feature-big-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #fff;
    color: #111;
    border-radius: 50px;
    padding: 11px 22px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    font-family: 'Montserrat', sans-serif;
    transition: background 0.2s;
}
.hw-feature-big-cta:hover { background: #F3F4F6; }

.hw-feature-stack { display: flex; flex-direction: column; gap: 16px; }
.hw-feature-card {
    border-radius: 18px;
    overflow: hidden;
    position: relative;
    height: 128px;
    text-decoration: none;
    display: flex;
    align-items: flex-end;
    transition: transform 0.25s;
}
.hw-feature-card:hover { transform: translateY(-3px); }
.hw-feature-card-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hw-feature-card-placeholder {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 20px;
    font-size: 50px;
    opacity: 0.3;
}
.hw-feature-card-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to right, rgba(0,0,0,0.65) 0%, rgba(0,0,0,0.1) 70%);
}
.hw-feature-card-content {
    position: relative;
    z-index: 2;
    padding: 20px 24px;
}
.hw-feature-card-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 4px;
}
.hw-feature-card-sub {
    font-size: 12px;
    color: rgba(255,255,255,0.8);
}
.hw-feature-card-arrow {
    position: absolute;
    right: 20px;
    bottom: 20px;
    z-index: 2;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.4);
    display: flex;
    align-items: center;
    justify-content: center;
}
.hw-feature-card-arrow svg { color: #fff; }

/* ── Stats 4 cartes Hello Work ────────────────── */
.hw-stats4-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-top: 40px;
}
.hw-s4-card {
    border-radius: 22px;
    padding: 32px 28px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 220px;
    position: relative;
    overflow: hidden;
}
.hw-s4-num {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(44px, 4vw, 64px);
    font-weight: 900;
    color: #111;
    line-height: 1;
    margin-bottom: 6px;
}
.hw-s4-num-white { color: #fff; }
.hw-s4-desc {
    font-size: 13px;
    color: #6B7280;
    line-height: 1.5;
}
.hw-s4-desc-white { color: rgba(255,255,255,0.75); }
.hw-s4-icon-circle {
    width: 52px; height: 52px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    margin-bottom: 16px;
    flex-shrink: 0;
}
.hw-s4-eyebrow {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-family: 'Montserrat', sans-serif;
    margin-bottom: 10px;
}
.hw-s4-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 16px;
    font-weight: 800;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 20px;
    flex: 1;
}
.hw-s4-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #111;
    color: #fff;
    border-radius: 50px;
    padding: 10px 20px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    font-family: 'Montserrat', sans-serif;
    width: fit-content;
    transition: background 0.2s;
}
.hw-s4-pill:hover { background: #333; }
.hw-s4-pill-white {
    background: #fff;
    color: #111;
}
.hw-s4-pill-white:hover { background: #F3F4F6; }

/* ── App section style Hello Work ─────────────── */
.hw-app-section {
    max-width: 1280px;
    margin: 0 auto;
    padding: 72px 32px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}
.hw-app-phones-wrap {
    background: #EEF2FF;
    border-radius: 28px;
    padding: 40px 32px 0;
    position: relative;
    overflow: hidden;
    min-height: 420px;
    display: flex;
    align-items: flex-end;
    justify-content: center;
}
.hw-app-phone-img {
    width: 72%;
    max-width: 340px;
    border-radius: 0;
    display: block;
    object-fit: contain;
    object-position: bottom center;
    position: relative;
    z-index: 2;
    filter: drop-shadow(0 20px 48px rgba(0,0,112,0.18));
}
.hw-app-text {}
.hw-app-text-title {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(28px, 3vw, 40px);
    font-weight: 900;
    color: #111;
    line-height: 1.15;
    margin-bottom: 18px;
}
.hw-app-text-title span { font-weight: 400; display: block; }
.hw-app-text-sub {
    font-size: 15px;
    color: #6B7280;
    line-height: 1.7;
    margin-bottom: 32px;
    max-width: 420px;
}
.hw-app-text-sub a { color: #111; font-weight: 600; }
.hw-app-btns-row {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.hw-app-store-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: #111;
    color: #fff;
    border-radius: 50px;
    padding: 14px 28px;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    font-family: 'Montserrat', sans-serif;
    transition: background 0.2s, transform 0.15s;
    white-space: nowrap;
}
.hw-app-store-btn:hover { background: #333; transform: translateY(-1px); }
.hw-app-store-btn svg { flex-shrink: 0; }

/* ── Articles section ─────────────────────────── */
.hw-articles-section {
    background: #FAF6F0;
    padding: 64px 0;
}
.hw-articles-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 12px;
    flex-wrap: wrap;
    gap: 12px;
}
.hw-articles-eyebrow {
    font-size: 12px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 20px;
}
.hw-see-all {
    font-size: 14px;
    font-weight: 600;
    color: #111;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: color 0.2s;
}
.hw-see-all:hover { color: #0070FF; }
.hw-articles-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 36px;
}
.hw-article-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    text-decoration: none;
    display: block;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    transition: all 0.2s;
}
.hw-article-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 28px rgba(0,0,0,0.12);
}
.hw-article-thumb {
    height: 160px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 60px;
}
.hw-article-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 10px;
    border-radius: 50px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    font-family: 'Montserrat', sans-serif;
}
.hw-article-body { padding: 18px 20px; }
.hw-article-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 700;
    color: #111;
    margin-bottom: 8px;
    line-height: 1.35;
}
.hw-article-excerpt { font-size: 13px; color: #6B7280; margin-bottom: 14px; line-height: 1.5; }
.hw-article-meta { font-size: 12px; color: #9CA3AF; }

/* ── Tags populaires ──────────────────────────── */
.hw-tags-label {
    font-size: 12px;
    font-weight: 700;
    color: #6B7280;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 14px;
}
.hw-tags { display: flex; flex-wrap: wrap; gap: 8px; }
.hw-tag {
    display: inline-block;
    padding: 7px 18px;
    background: #fff;
    border-radius: 50px;
    font-size: 13px;
    color: #374151;
    text-decoration: none;
    border: 1px solid #E5E7EB;
    transition: all 0.2s;
}
.hw-tag:hover { border-color: #111; color: #111; background: #F9FAFB; }

/* ── Outils ───────────────────────────────────── */
.hw-tools-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}
.hw-tool-card {
    border-radius: 20px;
    padding: 40px 40px 0;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    align-items: end;
    overflow: hidden;
    position: relative;
    min-height: 240px;
}
.hw-tool-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 20px;
    font-weight: 800;
    color: #111;
    margin-bottom: 10px;
    line-height: 1.3;
}
.hw-tool-sub {
    font-size: 13px;
    color: #6B7280;
    line-height: 1.6;
    margin-bottom: 20px;
    max-width: 240px;
}
.hw-tool-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #111;
    color: #fff;
    border-radius: 50px;
    padding: 10px 22px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    font-family: 'Montserrat', sans-serif;
    transition: background 0.2s;
    margin-bottom: 40px;
}
.hw-tool-btn:hover { background: #333; }
.hw-tool-mockup {
    align-self: end;
    position: relative;
    height: 190px;
    overflow: hidden;
}
.hw-tool-mockup img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: top;
    border-radius: 12px 12px 0 0;
    box-shadow: 0 -8px 32px rgba(0,0,0,0.15);
    display: block;
}
.hw-tool-mockup-placeholder {
    width: 100%;
    height: 100%;
    border-radius: 12px 12px 0 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 64px;
}

/* ── Catégories ───────────────────────────────── */
.hw-cats-section { background: #F9FAFB; border-top: 1px solid #E5E7EB; padding: 64px 0; }
.hw-cats-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 36px;
    flex-wrap: wrap;
    gap: 12px;
}
.hw-cats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 28px;
}
.hw-cat-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 800;
    color: #111;
    margin-bottom: 14px;
}
.hw-cat-pills { display: flex; flex-direction: column; gap: 8px; }
.hw-cat-pill {
    display: inline-block;
    border-radius: 50px;
    padding: 5px 14px;
    font-size: 12px;
    font-weight: 600;
    text-decoration: none;
    width: fit-content;
    border: 1px solid transparent;
    transition: all 0.2s;
}
.hw-cats-center { text-align: center; margin-top: 36px; }
.hw-btn-outline {
    display: inline-block;
    border: 1.5px solid #E5E7EB;
    border-radius: 50px;
    padding: 12px 32px;
    font-size: 14px;
    font-weight: 600;
    color: #111;
    text-decoration: none;
    background: #fff;
    transition: all 0.2s;
    font-family: 'Montserrat', sans-serif;
}
.hw-btn-outline:hover { border-color: #111; }

/* ── Panoramas ────────────────────────────────── */
.hw-panos-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}
.hw-pano-card {
    border-radius: 18px;
    overflow: hidden;
    position: relative;
    height: 200px;
    display: block;
    text-decoration: none;
    transition: transform 0.3s;
    background: #1a1a2e;
}
.hw-pano-card:hover { transform: scale(1.02); }
.hw-pano-card:hover .hw-pano-cta { background: rgba(255,255,255,0.25); }
.hw-pano-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    opacity: 0.65;
    transition: opacity 0.3s, transform 0.4s;
}
.hw-pano-card:hover .hw-pano-img { opacity: 0.8; transform: scale(1.05); }
.hw-pano-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.75) 0%, rgba(0,0,0,0.2) 60%);
    z-index: 1;
}
.hw-pano-body {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px 22px;
    z-index: 2;
    display: flex;
    align-items: flex-end;
    justify-content: space-between;
    gap: 12px;
}
.hw-pano-title {
    font-family: 'Montserrat', sans-serif;
    font-size: 15px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 4px;
    line-height: 1.3;
}
.hw-pano-sub { font-size: 12px; color: rgba(255,255,255,0.7); }
.hw-pano-cta {
    flex-shrink: 0;
    background: rgba(255,255,255,0.15);
    border: 1.5px solid rgba(255,255,255,0.4);
    color: #fff;
    border-radius: 50px;
    padding: 8px 16px;
    font-size: 12px;
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    white-space: nowrap;
    transition: background 0.2s;
}

/* ── Villes ───────────────────────────────────── */
.hw-cities-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    grid-template-rows: auto auto;
    gap: 16px;
}
.hw-city-card {
    border-radius: 18px;
    overflow: hidden;
    position: relative;
    display: flex;
    align-items: flex-end;
    text-decoration: none;
    transition: transform 0.3s;
    background: #111;
}
.hw-city-card:hover { transform: scale(1.01); }
.hw-city-card:hover .hw-city-img { transform: scale(1.06); opacity: 0.75; }
.hw-city-img {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    opacity: 0.6;
    transition: opacity 0.3s, transform 0.4s;
}
.hw-city-card.big { grid-row: 1 / 3; min-height: 300px; }
.hw-city-card.small { min-height: 138px; }
.hw-city-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.72) 0%, rgba(0,0,0,0.1) 60%);
    z-index: 1;
}
.hw-city-body {
    position: relative;
    z-index: 2;
    padding: 22px;
    width: 100%;
}
.hw-city-name {
    font-family: 'Montserrat', sans-serif;
    font-weight: 900;
    color: #fff;
    line-height: 1;
    margin-bottom: 4px;
}
.hw-city-name.lg { font-size: 30px; }
.hw-city-name.sm { font-size: 18px; }
.hw-city-sub { font-size: 13px; color: rgba(255,255,255,0.8); }

/* ── SEO links ────────────────────────────────── */
.hw-seo-section { border-top: 1px solid #E5E7EB; padding: 48px 0; }
.hw-seo-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 32px;
}
.hw-seo-label {
    font-size: 11px;
    font-weight: 700;
    color: #9CA3AF;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    margin-bottom: 12px;
}
.hw-seo-link {
    display: block;
    font-size: 13px;
    color: #6B7280;
    text-decoration: none;
    margin-bottom: 7px;
    transition: color 0.2s;
}
.hw-seo-link:hover { color: #111; }

/* ── Témoignages ──────────────────────────────── */
.hw-testi-section { background: #F9FAFB; padding: 64px 0; border-top: 1px solid #E5E7EB; }
.hw-testi-header { text-align: center; margin-bottom: 40px; }
.hw-testi-eyebrow {
    font-size: 12px;
    font-weight: 700;
    color: #0070FF;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
    font-family: 'Montserrat', sans-serif;
}
.hw-testi-title {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(22px, 3vw, 32px);
    font-weight: 800;
    color: #111;
}
.hw-testi-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
.hw-testi-card {
    background: #fff;
    border-radius: 16px;
    padding: 28px;
    border: 1px solid #E5E7EB;
    position: relative;
    overflow: hidden;
}
.hw-testi-quote {
    font-size: 64px;
    color: #F3F4F6;
    position: absolute;
    top: 8px;
    left: 16px;
    line-height: 1;
    font-family: Georgia, serif;
}
.hw-testi-stars { color: #FFC400; font-size: 14px; margin-bottom: 12px; position: relative; z-index: 1; }
.hw-testi-text {
    font-size: 14px;
    color: #374151;
    line-height: 1.65;
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
    font-style: italic;
}
.hw-testi-author { display: flex; align-items: center; gap: 12px; }
.hw-testi-avatar {
    width: 40px; height: 40px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 800; color: #fff; flex-shrink: 0;
}
.hw-testi-name { font-size: 13px; font-weight: 700; color: #111; }
.hw-testi-role { font-size: 12px; color: #9CA3AF; }

/* ── CTA Final ────────────────────────────────── */
.hw-cta-section { padding: 64px 0; }
.hw-cta-card {
    background: linear-gradient(135deg, #0070FF 0%, #0044CC 100%);
    border-radius: 24px;
    padding: 64px 48px;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.hw-cta-card::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 300px; height: 300px;
    border-radius: 50%;
    background: rgba(255,255,255,0.07);
}
.hw-cta-card::after {
    content: '';
    position: absolute;
    bottom: -40px; left: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
}
.hw-cta-title {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(24px, 4vw, 42px);
    font-weight: 800;
    color: #fff;
    margin-bottom: 12px;
    line-height: 1.2;
    position: relative;
    z-index: 2;
}
.hw-cta-sub {
    font-size: 16px;
    color: rgba(255,255,255,0.8);
    margin-bottom: 36px;
    position: relative;
    z-index: 2;
}
.hw-cta-btns {
    display: flex;
    gap: 14px;
    justify-content: center;
    flex-wrap: wrap;
    position: relative;
    z-index: 2;
}
.hw-cta-btn-white {
    display: inline-block;
    background: #fff;
    color: #0070FF;
    border-radius: 50px;
    padding: 14px 36px;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    font-family: 'Montserrat', sans-serif;
    transition: background 0.2s;
}
.hw-cta-btn-white:hover { background: #F0F9FF; }
.hw-cta-btn-yellow {
    display: inline-block;
    background: #FFC400;
    color: #111;
    border-radius: 50px;
    padding: 14px 36px;
    font-size: 15px;
    font-weight: 700;
    text-decoration: none;
    font-family: 'Montserrat', sans-serif;
    transition: background 0.2s;
}
.hw-cta-btn-yellow:hover { background: #e6b000; }

/* ── WhatsApp float ───────────────────────────── */
.wha-float {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 999;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #25D366;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 20px rgba(37,211,102,0.45);
    text-decoration: none;
    transition: transform 0.2s, box-shadow 0.2s;
}
.wha-float:hover { transform: scale(1.1); box-shadow: 0 6px 28px rgba(37,211,102,0.55); }

/* ── Séparateur horizontal ────────────────────── */
.hw-divider { border: none; border-top: 1px solid #E5E7EB; margin: 0; }

/* ── Responsive ───────────────────────────────── */
@media (max-width: 1023px) {
    .hw-carousel { grid-template-columns: repeat(2, 1fr); }
    .hw-feature-grid { grid-template-columns: 1fr; }
    .hw-feature-big { min-height: 320px; }
    .hw-stats4-grid { grid-template-columns: repeat(2, 1fr); }
    .hw-app-section { grid-template-columns: 1fr; gap: 40px; padding: 48px 24px; }
    .hw-app-phones-wrap { min-height: 300px; }
    .hw-articles-grid { grid-template-columns: repeat(2, 1fr); }
    .hw-tools-grid { grid-template-columns: 1fr; }
    .hw-tool-card { padding: 32px 28px 0; }
    .hw-cats-grid { grid-template-columns: repeat(2, 1fr); }
    .hw-panos-grid { grid-template-columns: 1fr 1fr; }
    .hw-cities-grid { grid-template-columns: 1fr 1fr; grid-template-rows: auto; }
    .hw-city-card.big { grid-row: auto; min-height: 200px; }
    .hw-seo-grid { grid-template-columns: repeat(2, 1fr); }
    .hw-testi-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 767px) {
    .hw-hero { height: 320px; }
    .hw-hero-text { right: 5%; left: 5%; text-align: left; }
    .hw-search-bar { grid-template-columns: 1fr auto; }
    .hw-search-field:last-of-type { display: none; }
    .hw-carousel { grid-template-columns: 1fr; }
    .hw-feature-stack { display: none; }
    .hw-articles-grid { grid-template-columns: 1fr; }
    .hw-cats-grid { grid-template-columns: 1fr 1fr; }
    .hw-panos-grid { grid-template-columns: 1fr; }
    .hw-cities-grid { grid-template-columns: 1fr; }
    .hw-seo-grid { grid-template-columns: 1fr 1fr; }
    .hw-testi-grid { grid-template-columns: 1fr; }
    .hw-cta-card { padding: 40px 24px; }
    .hw-app-section { padding: 32px 16px; gap: 28px; }
    .hw-app-phones-wrap { min-height: 240px; padding: 32px 24px 0; }
    .hw-app-btns-row { flex-direction: column; }
    .hw-s4-card { padding: 24px 20px; min-height: 180px; }
    .hw-stats4-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
}
</style>

<!-- ══════════════════════════════════════════════════════
     HERO — Image full-bleed + texte overlay droite
     Exactement comme Hello Work
═══════════════════════════════════════════════════════ -->
<section class="hw-hero">
    <!-- Image pleine largeur -->
    <img src="/assets/images/taxi-hero.png"
         alt="Propriétaire de taxi remet les clés à son chauffeur à Brazzaville"
         class="hw-hero-img">
    <!-- Overlay dégradé pour lisibilité -->
    <div class="hw-hero-overlay"></div>
    <!-- Texte overlay côté droit -->
    <div class="hw-hero-text">
        <h1>
            Notre job, vous aider à trouver le vôtre parmi
            <strong><span data-target="<?= $displayOffers ?>"><?= number_format($displayOffers,0,',',' ') ?></span> offres</strong>
        </h1>
    </div>
</section>

<!-- ══════════════════════════════════════════════════════
     SEARCH BAR flottante — sous l'image, centrée
     Style Hello Work : 2 champs + bouton rond noir
═══════════════════════════════════════════════════════ -->
<div class="hw-search-wrap">
    <form action="/offres" method="GET" class="hw-search-bar">
        <div class="hw-search-field">
            <label for="s-quoi">QUOI ?</label>
            <input type="text" id="s-quoi" name="q" placeholder="Chauffeur, type de taxi, recette…">
        </div>
        <div class="hw-search-field">
            <label for="s-ou">OÙ ?</label>
            <select id="s-ou" name="ville">
                <option value="">Ville, quartier, zone…</option>
                <option>Brazzaville</option>
                <option>Pointe-Noire</option>
                <option>Dolisie</option>
                <option>Nkayi</option>
            </select>
        </div>
        <button type="submit" class="hw-search-btn" aria-label="Rechercher">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </button>
    </form>
</div>

<!-- Pills de filtres rapides -->
<div class="hw-pills">
    <a href="/offres?type=temps-plein" class="hw-pill">
        <span class="hw-pill-icon">🌞</span> Temps plein
    </a>
    <a href="/offres?type=temps-partiel" class="hw-pill">
        <span class="hw-pill-icon">🕐</span> Temps partiel
    </a>
    <a href="/offres?type=climatise" class="hw-pill">
        <span class="hw-pill-icon">❄️</span> Taxi climatisé
    </a>
    <a href="/offres?urgence=1" class="hw-pill">
        <span class="hw-pill-icon">⚡</span> Urgent
    </a>
</div>

<!-- Banner FLASH IA (équivalent Coach Emploi) -->
<div class="hw-banner" style="margin-bottom: 20px;">
    <div class="hw-banner-inner">
        <div class="hw-banner-avatar">🚖</div>
        <div class="hw-banner-text">
            <strong>Découvrez FLASH Accompagnement</strong>
            <span>Notre équipe vous accompagne à chaque étape de votre recherche d'emploi taxi au Congo</span>
        </div>
        <a href="/contact" class="hw-banner-cta">Découvrir</a>
    </div>
</div>

<hr class="hw-divider">

<!-- ══════════════════════════════════════════════════════
     SIMPLE COMME FLASH — 3 sections alternées style Hello Work
     Image illustrée gauche/droite + texte avec icône + CTA
═══════════════════════════════════════════════════════ -->
<style>
/* ── Sections alternées ────────────────────────── */
.hw-alt-section { padding: 80px 0; }
.hw-alt-section + .hw-alt-section { padding-top: 0; }
.hw-alt-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}
.hw-alt-grid.reverse { direction: rtl; }
.hw-alt-grid.reverse > * { direction: ltr; }

/* Card illustrée */
.hw-alt-card {
    border-radius: 24px;
    overflow: hidden;
    position: relative;
    min-height: 340px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.hw-alt-card-inner {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 340px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 32px;
}
/* Photo taxi dans la card */
.hw-alt-photo {
    width: 75%;
    max-width: 280px;
    border-radius: 16px;
    box-shadow: 0 16px 48px rgba(0,0,0,0.2);
    display: block;
    object-fit: cover;
    aspect-ratio: 4/3;
    position: relative;
    z-index: 2;
}
/* Badges flottants style Hello Work */
.hw-alt-badge {
    position: absolute;
    background: #fff;
    border-radius: 50px;
    padding: 8px 16px;
    font-family: 'Montserrat', sans-serif;
    font-size: 13px;
    font-weight: 700;
    color: #111;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    z-index: 3;
    display: flex;
    align-items: center;
    gap: 6px;
    white-space: nowrap;
}
.hw-alt-badge-sm {
    position: absolute;
    background: #fff;
    border-radius: 12px;
    padding: 10px 14px;
    font-family: 'Montserrat', sans-serif;
    font-size: 12px;
    font-weight: 700;
    color: #111;
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    z-index: 3;
    min-width: 160px;
}
.hw-alt-badge-sm .badge-title {
    font-size: 13px;
    font-weight: 800;
    color: #111;
    margin-bottom: 8px;
}
.hw-alt-badge-sm .badge-step {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11px;
    color: #9CA3AF;
    margin-bottom: 4px;
}
.hw-alt-badge-sm .badge-step.active { color: #0070FF; font-weight: 700; }
.hw-alt-badge-sm .badge-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #E5E7EB;
    flex-shrink: 0;
}
.hw-alt-badge-sm .badge-dot.active { background: #0070FF; }

/* Icône cercle coloré */
.hw-alt-icon {
    width: 52px;
    height: 52px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin-bottom: 20px;
    flex-shrink: 0;
}
/* Texte côté */
.hw-alt-text {}
.hw-alt-text h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(24px, 3vw, 36px);
    font-weight: 400;
    color: #111;
    line-height: 1.2;
    margin-bottom: 16px;
}
.hw-alt-text h2 strong { font-weight: 800; }
.hw-alt-text p {
    font-size: 15px;
    color: #6B7280;
    line-height: 1.65;
    margin-bottom: 24px;
}
.hw-alt-cta {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border: 1.5px solid #D1D5DB;
    border-radius: 50px;
    padding: 11px 24px;
    font-family: 'Montserrat', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #111;
    text-decoration: none;
    transition: all 0.2s;
    background: #fff;
}
.hw-alt-cta:hover {
    border-color: #111;
    background: #F9FAFB;
}

/* Section titre principal */
.hw-simple-title {
    max-width: 1200px;
    margin: 0 auto;
    padding: 72px 24px 48px;
}
.hw-simple-title h2 {
    font-family: 'Montserrat', sans-serif;
    font-size: clamp(28px, 4vw, 46px);
    font-weight: 400;
    color: #111;
    line-height: 1.15;
}
.hw-simple-title h2 strong { font-weight: 800; display: block; }

/* Responsive */
@media (max-width: 900px) {
    .hw-alt-grid { grid-template-columns: 1fr; gap: 32px; }
    .hw-alt-grid.reverse { direction: ltr; }
    .hw-alt-card { min-height: 260px; }
    .hw-alt-card-inner { min-height: 260px; }
}
</style>

<hr class="hw-divider">

<!-- ══════════════════════════════════════════════════════
     DES PROPRIÉTAIRES QUI RECRUTENT — Carousel cards
     Exactement comme "Des entreprises qui recrutent"
═══════════════════════════════════════════════════════ -->
<div class="hw-section" style="padding-bottom:0;">
    <h2 class="hw-title">Des propriétaires<br><strong>qui recrutent</strong></h2>

    <div class="hw-carousel-wrap">
        <div class="hw-carousel" id="ownersCarousel">
            <?php
            $ownerCards = [
                [
                    'name'  => 'M. Moukassa – Brazzaville',
                    'jobs'  => '3 offres actives',
                    'img'   => '/assets/images/taxi-card-1.jpg',
                    'ville' => 'Brazzaville',
                ],
                [
                    'name'  => 'Mme Bouanga – Pointe-Noire',
                    'jobs'  => '2 offres actives',
                    'img'   => '/assets/images/taxi-card-2.jpg',
                    'ville' => 'Pointe-Noire',
                ],
                [
                    'name'  => 'M. Nganga – Dolisie',
                    'jobs'  => '1 offre active',
                    'img'   => '/assets/images/taxi-card-3.jpg',
                    'ville' => 'Dolisie',
                ],
                [
                    'name'  => 'Taxi Élite – Brazzaville',
                    'jobs'  => '4 offres actives',
                    'img'   => '/assets/images/taxi-card-4.jpg',
                    'ville' => 'Brazzaville',
                ],
                [
                    'name'  => 'Rapid Auto – Nkayi',
                    'jobs'  => '2 offres actives',
                    'img'   => '/assets/images/taxi-card-1.jpg',
                    'ville' => 'Nkayi',
                ],
                [
                    'name'  => 'Congo Taxi Pro – PNR',
                    'jobs'  => '5 offres actives',
                    'img'   => '/assets/images/taxi-card-2.jpg',
                    'ville' => 'Pointe-Noire',
                ],
            ];
            foreach (array_slice($ownerCards, 0, 3) as $o): ?>
            <a href="/offres?ville=<?= urlencode($o['ville']) ?>" class="hw-owner-card">
                <!-- Photo taxi pleine largeur -->
                <div style="
                    width:100%; height:210px; overflow:hidden;
                    background: linear-gradient(135deg,#e8f5e9,#c8e6c9);
                    position:relative;
                ">
                    <img src="<?= $o['img'] ?>"
                         alt="Taxi <?= htmlspecialchars($o['ville']) ?>"
                         style="width:100%;height:100%;object-fit:cover;object-position:center;"
                         onerror="this.parentElement.style.background='linear-gradient(135deg,#1a4a2e,#008A3D)';this.style.display='none';">
                    <!-- Gradient overlay bas -->
                    <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.25) 0%,transparent 50%);"></div>
                    <!-- Badge ville -->
                    <div style="position:absolute;top:12px;left:12px;background:rgba(0,0,0,0.55);backdrop-filter:blur(4px);color:#fff;border-radius:50px;padding:4px 12px;font-size:12px;font-weight:700;font-family:'Montserrat',sans-serif;">
                        📍 <?= htmlspecialchars($o['ville']) ?>
                    </div>
                </div>
                <div class="hw-owner-info">
                    <div class="hw-owner-info-left">
                        <div class="hw-owner-name"><?= htmlspecialchars($o['name']) ?></div>
                        <div class="hw-owner-count"><?= $o['jobs'] ?></div>
                    </div>
                    <div class="hw-owner-arrow" style="background:#111;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Navigation carousel -->
        <div class="hw-carousel-nav">
            <div style="display:flex;align-items:center;gap:20px;">
                <!-- Flèches gauche/droite -->
                <div class="hw-carousel-arrows">
                    <button class="hw-arrow-btn" onclick="slideOwners(-1)" aria-label="Précédent">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                    </button>
                    <button class="hw-arrow-btn" onclick="slideOwners(1)" aria-label="Suivant">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                    </button>
                </div>
                <!-- Dots -->
                <div class="hw-carousel-dots">
                    <div class="hw-dot" id="dot0"></div>
                    <div class="hw-dot inactive" id="dot1"></div>
                </div>
            </div>
            <div></div>
        </div>
    </div>

    <a href="/offres" class="hw-btn-black">
        Voir toutes les offres
    </a>
</div>

<hr class="hw-divider" style="margin-top:64px;">

<!-- ══════════════════════════════════════════════════════
     TROUVER UN CHAUFFEUR — 2 colonnes style Hello Work
     Grande card photo gauche + 3 mini cards droite
═══════════════════════════════════════════════════════ -->
<div class="hw-section">
    <h2 class="hw-title">Trouver un chauffeur ?<br><strong>Simple comme FLASH</strong></h2>

    <div class="hw-feature-grid">

        <!-- Grande card gauche avec photo -->
        <a href="/chauffeurs" class="hw-feature-big" style="background:linear-gradient(135deg,#6366F1 0%,#3730A3 100%);">
            <div class="hw-feature-big-placeholder">🚗</div>
            <div class="hw-feature-big-overlay"></div>
            <div class="hw-feature-big-content">
                <span class="hw-feature-big-badge">⚡ Pour les chauffeurs</span>
                <div class="hw-feature-big-title">
                    Déposez votre profil<br>chauffeur en 5 minutes
                </div>
                <a href="/chauffeurs" class="hw-feature-big-cta">
                    Je dépose mon CV
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </a>

        <!-- 3 petites cards droite -->
        <div class="hw-feature-stack">

            <!-- Card 1 — Vert -->
            <a href="/offres" class="hw-feature-card" style="background:linear-gradient(135deg,#14532d,#008A3D);">
                <div class="hw-feature-card-placeholder">✅</div>
                <div class="hw-feature-card-overlay"></div>
                <div class="hw-feature-card-content">
                    <div class="hw-feature-card-title">Suivez votre candidature</div>
                    <div class="hw-feature-card-sub">Contacté directement, sans intermédiaire</div>
                </div>
                <div class="hw-feature-card-arrow">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </a>

            <!-- Card 2 — Orange -->
            <a href="/offres" class="hw-feature-card" style="background:linear-gradient(135deg,#7c2d12,#ea580c);">
                <div class="hw-feature-card-placeholder">📋</div>
                <div class="hw-feature-card-overlay"></div>
                <div class="hw-feature-card-content">
                    <div class="hw-feature-card-title">Des offres qui ne cachent rien</div>
                    <div class="hw-feature-card-sub">Recette, horaires, type de taxi — tout affiché</div>
                </div>
                <div class="hw-feature-card-arrow">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </a>

            <!-- Card 3 — Bleu -->
            <a href="/proprietaires" class="hw-feature-card" style="background:linear-gradient(135deg,#1e3a8a,#2563eb);">
                <div class="hw-feature-card-placeholder">🤝</div>
                <div class="hw-feature-card-overlay"></div>
                <div class="hw-feature-card-content">
                    <div class="hw-feature-card-title">Des propriétaires transparents</div>
                    <div class="hw-feature-card-sub">Sélectionnés par FLASH pour leur sérieux</div>
                </div>
                <div class="hw-feature-card-arrow">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </div>
            </a>
        </div>
    </div>
</div>

<hr class="hw-divider">

<!-- ══════════════════════════════════════════════════════
     STATS — 4 cartes style Hello Work
═══════════════════════════════════════════════════════ -->
<div class="hw-section" style="padding-bottom:0;">
    <h2 class="hw-title">Préparez-vous à<br><strong>décrocher votre job !</strong></h2>

    <div class="hw-stats4-grid">

        <!-- Carte 1 — Lavande : nombre chauffeurs -->
        <div class="hw-s4-card" style="background:#EEF2FF;">
            <div>
                <div class="hw-s4-num">
                    <span data-target="<?= $displayDrivers ?>"><?= $displayDrivers ?></span>
                </div>
                <div class="hw-s4-desc">chauffeurs déjà inscrits sur FLASH Congo</div>
            </div>
            <div style="margin-top:20px;font-size:13px;color:#4F46E5;font-weight:600;">soyez le prochain à être vu !</div>
        </div>

        <!-- Carte 2 — Bleu vif : CTA chauffeur -->
        <div class="hw-s4-card" style="background:#0070FF;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <div class="hw-s4-icon-circle">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                </div>
                <div class="hw-s4-eyebrow" style="color:rgba(255,255,255,0.6);">Pour les chauffeurs</div>
                <div class="hw-s4-title">SOYEZ VISIBLE AUPRÈS DES PROPRIÉTAIRES</div>
            </div>
            <a href="/chauffeurs" class="hw-s4-pill hw-s4-pill-white">
                Déposer mon CV
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <!-- Carte 3 — Jaune pâle : nombre offres -->
        <div class="hw-s4-card" style="background:#FEFCE8;">
            <div>
                <div class="hw-s4-num">
                    <span data-target="<?= $displayOffers ?>"><?= $displayOffers ?></span>
                </div>
                <div class="hw-s4-desc">offres actives sur la plateforme</div>
            </div>
            <div style="margin-top:20px;font-size:13px;color:#CA8A04;font-weight:600;">on vous envoie celles qui collent ?</div>
        </div>

        <!-- Carte 4 — Jaune vif : CTA alerte -->
        <div class="hw-s4-card" style="background:#FFC400;display:flex;flex-direction:column;justify-content:space-between;">
            <div>
                <div class="hw-s4-icon-circle" style="background:rgba(0,0,0,0.12);">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
                </div>
                <div class="hw-s4-eyebrow" style="color:rgba(0,0,0,0.5);">Pour les chauffeurs</div>
                <div class="hw-s4-title" style="color:#111;">SOYEZ ALERTÉ RAPIDEMENT</div>
            </div>
            <a href="/chauffeurs" class="hw-s4-pill">
                Créer mon alerte
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

    </div>
</div>

<!-- ══════════════════════════════════════════════════════
     APP — Style Hello Work : phones gauche, texte droite
═══════════════════════════════════════════════════════ -->
<div class="hw-app-section">

    <!-- Gauche : fond lavande + photo téléphone FLASH -->
    <div class="hw-app-phones-wrap">
        <?php if($img = imgPath('app-screen-1')): ?>
        <img src="<?= $img ?>" class="hw-app-phone-img" alt="App FLASH">
        <?php endif; ?>
    </div>

    <!-- Droite : texte + boutons noirs -->
    <div class="hw-app-text">
        <h2 class="hw-app-text-title">
            <span>Téléchargez l'app</span>
            <strong>pour ne rien manquer</strong>
        </h2>
        <p class="hw-app-text-sub">
            Recevez les offres en temps réel, gérez votre profil et contactez propriétaires ou chauffeurs directement <a href="#">avec l'app</a>.
        </p>
        <div class="hw-app-btns-row">
            <a href="#" class="hw-app-store-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M3.18 23.76c.33.18.72.18 1.05 0l10.5-6.06L12 15l-8.82 8.76zM.09 1.56C.03 1.8 0 2.07 0 2.37v19.26c0 .3.03.57.09.81L12 12 .09 1.56zM23.91 10.68l-2.79-1.62L18 12l3.12 2.94 2.79-1.62c.78-.48.78-1.68 0-2.64zM4.23.24C3.9.06 3.51.06 3.18.24L12 9l2.73-2.7L4.23.24z"/></svg>
                Google Play
            </a>
            <a href="#" class="hw-app-store-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                App Store
            </a>
        </div>
    </div>

</div>

<hr class="hw-divider">

<!-- ══════════════════════════════════════════════════════
     ARTICLES — Fond beige crème, style Hello Work
═══════════════════════════════════════════════════════ -->
<section class="hw-articles-section">
    <div class="hw-section hw-section-sm" style="padding-top:64px;">
        <div class="hw-articles-header">
            <h2 class="hw-title" style="margin-bottom:0;">Tout connaître<br><strong>du monde du taxi au Congo</strong></h2>
            <a href="/conseils" class="hw-see-all">
                Voir tous les articles
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
        <p class="hw-articles-eyebrow" style="margin-top:8px;">Nos articles fraîchement sortis</p>

        <?php
        $staticArts = [
            ['comment-rentabiliser-son-taxi-au-congo','Comment rentabiliser son taxi au Congo','Gestion',5,'Découvrez les meilleures stratégies pour maximiser vos revenus de chauffeur.','#EEF2FF','#4F46E5','🚗'],
            ['comment-choisir-un-bon-chauffeur','Comment choisir un bon chauffeur de taxi','Recrutement',4,'Les critères essentiels pour sélectionner un chauffeur fiable et sérieux.','#F0FDF4','#16A34A','👤'],
            ['comment-fixer-la-recette-journaliere','Comment fixer la recette journalière','Finance',3,'Guide pratique pour établir une recette juste entre propriétaire et chauffeur.','#FFF7ED','#EA580C','💰'],
        ];
        $displayArts = !empty($articles) ? $articles : null;
        $emojis = ['🚗','👤','💰'];
        $bgs    = ['#EEF2FF','#F0FDF4','#FFF7ED'];
        $colors = ['#4F46E5','#16A34A','#EA580C'];
        $idx = 0;
        ?>

        <div class="hw-articles-grid">
            <?php if ($displayArts): foreach (array_slice($displayArts,0,3) as $a): ?>
            <a href="/conseils/<?= htmlspecialchars($a['slug']) ?>" class="hw-article-card">
                <div class="hw-article-thumb" style="background:<?= $bgs[$idx%3] ?>;">
                    <?= $emojis[$idx%3] ?>
                    <span class="hw-article-badge" style="background:<?= $colors[$idx%3] ?>;"><?= htmlspecialchars($a['category']) ?></span>
                </div>
                <div class="hw-article-body">
                    <div class="hw-article-title"><?= htmlspecialchars($a['title']) ?></div>
                    <div class="hw-article-excerpt"><?= htmlspecialchars(substr($a['excerpt'],0,90)) ?>…</div>
                    <div class="hw-article-meta">📖 <?= $a['reading_time'] ?> min de lecture</div>
                </div>
            </a>
            <?php $idx++; endforeach;
            else: foreach ($staticArts as $a): ?>
            <a href="/conseils/<?= $a[0] ?>" class="hw-article-card">
                <div class="hw-article-thumb" style="background:<?= $a[5] ?>;">
                    <?= $a[7] ?>
                    <span class="hw-article-badge" style="background:<?= $a[6] ?>;"><?= $a[2] ?></span>
                </div>
                <div class="hw-article-body">
                    <div class="hw-article-title"><?= $a[1] ?></div>
                    <div class="hw-article-excerpt"><?= $a[4] ?></div>
                    <div class="hw-article-meta">📖 <?= $a[3] ?> min de lecture</div>
                </div>
            </a>
            <?php endforeach; endif; ?>
        </div>

        <!-- Tags populaires -->
        <p class="hw-tags-label">Les sujets les plus populaires</p>
        <div class="hw-tags">
            <?php foreach(['Recette journalière','Entretien taxi','Business taxi Congo','Chauffeur fiable','Taxi Brazzaville','Permis de conduire','Taxi vert-blanc','Réussite taxi','Taxi Pointe-Noire'] as $tag): ?>
            <a href="/conseils?q=<?= urlencode($tag) ?>" class="hw-tag"><?= $tag ?></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<hr class="hw-divider">

<!-- ══════════════════════════════════════════════════════
     OUTILS — 2 cards illustrées avec photo
═══════════════════════════════════════════════════════ -->
<div class="hw-section">
    <h2 class="hw-title">Les outils pour<br><strong>trouver votre job</strong></h2>
    <div class="hw-tools-grid">
        <div class="hw-tool-card" style="background:linear-gradient(135deg,#FFF0E6,#FFE8D0);">
            <div>
                <div class="hw-tool-title">Créez votre<br>fiche chauffeur</div>
                <div class="hw-tool-sub">Présentez votre expérience et soyez visible par les propriétaires qui recrutent au Congo.</div>
                <a href="/chauffeurs" class="hw-tool-btn">
                    Créer ma fiche
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="hw-tool-mockup">
                <img src="/assets/images/taxi-card-1.jpg" alt="Fiche chauffeur"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div class="hw-tool-mockup-placeholder" style="background:linear-gradient(180deg,#FFD5A8,#FFB380);display:none;">📄</div>
            </div>
        </div>
        <div class="hw-tool-card" style="background:linear-gradient(135deg,#E8F0FF,#D0E2FF);">
            <div>
                <div class="hw-tool-title">Publiez votre<br>offre de taxi</div>
                <div class="hw-tool-sub">Publiez vos conditions et recevez les candidatures de chauffeurs disponibles.</div>
                <a href="/proprietaires" class="hw-tool-btn">
                    Publier une offre
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="hw-tool-mockup">
                <img src="/assets/images/taxi-card-2.jpg" alt="Offre taxi"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">
                <div class="hw-tool-mockup-placeholder" style="background:linear-gradient(180deg,#A8CBFF,#80AFFF);display:none;">📋</div>
            </div>
        </div>
    </div>
</div>

<hr class="hw-divider">

<!-- ══════════════════════════════════════════════════════
     CATÉGORIES — Pills colorées 4 colonnes
═══════════════════════════════════════════════════════ -->
<section class="hw-cats-section">
    <div class="hw-section hw-section-sm" style="padding-top:64px;">
        <div class="hw-cats-header">
            <h2 class="hw-title" style="margin-bottom:0;">On a classé<br><strong>tous nos jobs !</strong></h2>
            <a href="/offres" class="hw-btn-outline">Voir toutes les offres</a>
        </div>
        <div class="hw-cats-grid">
            <?php
            $cats = [
                ['Taxi ordinaire',['Taxi vert-blanc Brazzaville','Taxi bleu-blanc Pointe-Noire','Taxi ordinaire Dolisie','Taxi Nkayi','Taxi sans expérience'],'#6366F1'],
                ['Taxi climatisé',['Taxi climatisé Brazzaville','Taxi premium Congo','Véhicule climatisé','Conducteur VIP','Taxi confort'],'#8B5CF6'],
                ['Temps plein',['Chauffeur journée','Poste permanent','6j/7 Brazzaville','Contrat long terme','Temps plein Dolisie'],'#EC4899'],
                ['Temps partiel',['Chauffeur week-end','Mi-temps taxi','Soirées uniquement','Flexible Congo','Partiel Pointe-Noire'],'#F59E0B'],
            ];
            foreach ($cats as $cat): ?>
            <div>
                <div class="hw-cat-title"><?= $cat[0] ?></div>
                <div class="hw-cat-pills">
                    <?php foreach ($cat[1] as $item): ?>
                    <a href="/offres?q=<?= urlencode($item) ?>" class="hw-cat-pill"
                       style="background:<?= $cat[2] ?>18;color:<?= $cat[2] ?>;border-color:<?= $cat[2] ?>30;"
                       onmouseover="this.style.background='<?= $cat[2] ?>';this.style.color='#fff'"
                       onmouseout="this.style.background='<?= $cat[2] ?>18';this.style.color='<?= $cat[2] ?>'">
                        <?= $item ?>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="hw-cats-center">
            <a href="/offres" class="hw-btn-outline">Voir toutes les offres d'emploi</a>
        </div>
    </div>
</section>

<hr class="hw-divider">

<!-- ══════════════════════════════════════════════════════
     PANORAMAS — 4 cartes photo 2×2
═══════════════════════════════════════════════════════ -->
<div class="hw-section">
    <h2 class="hw-title">Explorez nos panoramas<br><strong style="font-size:clamp(18px,2vw,26px);font-weight:400;color:#6B7280;">pour éclairer vos choix professionnels</strong></h2>
    <div class="hw-panos-grid">
        <?php
        $panos = [
            ['Le grand panorama<br>des métiers',    'Découvrez tous les aspects du taxi au Congo', '/assets/images/taxi-card-1.jpg', 'Découvrir les métiers'],
            ['Le grand panorama<br>des salaires',   'Recettes et revenus moyens selon la ville',   '/assets/images/taxi-card-2.jpg', 'Découvrir les salaires'],
            ['Le grand panorama<br>des compétences','Ce que cherchent vraiment les propriétaires', '/assets/images/taxi-hero.png',   'Découvrir les compétences'],
            ['Le grand panorama<br>des formations', 'Se former pour mieux conduire et gagner plus', '/assets/images/taxi-card-3.jpg','Découvrir les formations'],
        ];
        foreach ($panos as $p): ?>
        <a href="/conseils" class="hw-pano-card">
            <img src="<?= $p[2] ?>" alt="<?= strip_tags($p[0]) ?>" class="hw-pano-img"
                 onerror="this.style.display='none'">
            <div class="hw-pano-overlay"></div>
            <div class="hw-pano-body">
                <div>
                    <div class="hw-pano-title"><?= $p[0] ?></div>
                    <div class="hw-pano-sub"><?= $p[1] ?></div>
                </div>
                <div class="hw-pano-cta"><?= $p[3] ?> →</div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>

<hr class="hw-divider">

<!-- ══════════════════════════════════════════════════════
     VILLES — Grande Brazzaville + 4 petites (photos)
═══════════════════════════════════════════════════════ -->
<div class="hw-section" style="padding-top:48px;">
    <h2 class="hw-title">Quelle ville pour<br><strong>votre prochain job ?</strong></h2>
    <div class="hw-cities-grid">

        <a href="/offres?ville=Brazzaville" class="hw-city-card big">
            <img src="/assets/images/city-brazzaville.jpg" alt="Brazzaville" class="hw-city-img"
                 onerror="this.style.display='none';this.parentElement.style.background='linear-gradient(135deg,#1e3a8a,#0070FF)'">
            <div class="hw-city-overlay"></div>
            <div class="hw-city-body">
                <div class="hw-city-name lg">Brazzaville</div>
                <div class="hw-city-sub">Capitale · <?= $offersCount > 0 ? $offersCount : '5' ?>+ offres actives</div>
                <div style="margin-top:14px;display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.15);border:1.5px solid rgba(255,255,255,0.35);color:#fff;border-radius:50px;padding:7px 16px;font-size:12px;font-weight:700;font-family:Montserrat,sans-serif;">
                    Voir les offres →
                </div>
            </div>
        </a>

        <a href="/offres?ville=Pointe-Noire" class="hw-city-card small">
            <img src="/assets/images/city-pointe-noire.jpg" alt="Pointe-Noire" class="hw-city-img"
                 onerror="this.style.display='none';this.parentElement.style.background='linear-gradient(135deg,#064E3B,#008A3D)'">
            <div class="hw-city-overlay"></div>
            <div class="hw-city-body">
                <div class="hw-city-name sm">Pointe-Noire</div>
                <div class="hw-city-sub">Port pétrolier</div>
            </div>
        </a>

        <a href="/offres?ville=Dolisie" class="hw-city-card small">
            <img src="/assets/images/city-dolisie.jpg" alt="Dolisie" class="hw-city-img"
                 onerror="this.style.display='none';this.parentElement.style.background='linear-gradient(135deg,#78350F,#B45309)'">
            <div class="hw-city-overlay"></div>
            <div class="hw-city-body">
                <div class="hw-city-name sm">Dolisie</div>
                <div class="hw-city-sub">3e ville du Congo</div>
            </div>
        </a>

        <a href="/offres?ville=Nkayi" class="hw-city-card small">
            <img src="/assets/images/city-nkayi.jpg" alt="Nkayi" class="hw-city-img"
                 onerror="this.style.display='none';this.parentElement.style.background='linear-gradient(135deg,#4C1D95,#7C3AED)'">
            <div class="hw-city-overlay"></div>
            <div class="hw-city-body">
                <div class="hw-city-name sm">Nkayi</div>
                <div class="hw-city-sub">Ville industrielle</div>
            </div>
        </a>

        <a href="/offres" class="hw-city-card small">
            <img src="/assets/images/taxi-card-4.jpg" alt="Autres villes" class="hw-city-img"
                 onerror="this.style.display='none';this.parentElement.style.background='linear-gradient(135deg,#1F2937,#374151)'">
            <div class="hw-city-overlay"></div>
            <div class="hw-city-body">
                <div class="hw-city-name sm">Autres villes</div>
                <div class="hw-city-sub">Tout le Congo</div>
            </div>
        </a>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════
     CTA FINAL
═══════════════════════════════════════════════════════ -->
<section class="hw-cta-section">
    <div style="max-width:1200px;margin:0 auto;padding:0 24px;">
        <div class="hw-cta-card">
            <h2 class="hw-cta-title">Prêt à démarrer<br>votre activité taxi ?</h2>
            <p class="hw-cta-sub">Rejoignez FLASH dès maintenant. Gratuit, rapide, efficace.</p>
            <div class="hw-cta-btns">
                <a href="/chauffeurs" class="hw-cta-btn-white">Je dépose mon CV</a>
                <a href="/proprietaires" class="hw-cta-btn-yellow">Je cherche un chauffeur</a>
            </div>
        </div>
    </div>
</section>

<script>
/* ── Carousel propriétaires ───────────────────── */
(function() {
    var cards = <?= json_encode($ownerCards) ?>;
    var current = 0;
    var perPage = window.innerWidth >= 1024 ? 3 : window.innerWidth >= 768 ? 2 : 1;
    var total = Math.ceil(cards.length / perPage);

    function renderCards(page) {
        var carousel = document.getElementById('ownersCarousel');
        if (!carousel) return;
        var start = page * perPage;
        var slice = cards.slice(start, start + perPage);
        carousel.innerHTML = slice.map(function(o) {
            return '<a href="/offres?ville=' + encodeURIComponent(o.ville) + '" class="hw-owner-card">' +
                '<div style="width:100%;height:210px;overflow:hidden;background:linear-gradient(135deg,#1a4a2e,#008A3D);position:relative;">' +
                    '<img src="' + o.img + '" alt="Taxi ' + o.ville + '" style="width:100%;height:100%;object-fit:cover;object-position:center;" onerror="this.style.display=\'none\'">' +
                    '<div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(0,0,0,0.25) 0%,transparent 50%);"></div>' +
                    '<div style="position:absolute;top:12px;left:12px;background:rgba(0,0,0,0.55);backdrop-filter:blur(4px);color:#fff;border-radius:50px;padding:4px 12px;font-size:12px;font-weight:700;font-family:Montserrat,sans-serif;">📍 ' + o.ville + '</div>' +
                '</div>' +
                '<div class="hw-owner-info">' +
                    '<div class="hw-owner-info-left">' +
                        '<div class="hw-owner-name">' + o.name + '</div>' +
                        '<div class="hw-owner-count">' + o.jobs + '</div>' +
                    '</div>' +
                    '<div class="hw-owner-arrow" style="background:#111;">' +
                        '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>' +
                    '</div>' +
                '</div>' +
            '</a>';
        }).join('');
        // Update dots
        for (var i = 0; i < total; i++) {
            var dot = document.getElementById('dot' + i);
            if (dot) {
                dot.className = i === page ? 'hw-dot' : 'hw-dot inactive';
            }
        }
    }

    window.slideOwners = function(dir) {
        current = (current + dir + total) % total;
        renderCards(current);
    };

    // Init dots
    var dotsEl = document.querySelector('.hw-carousel-dots');
    if (dotsEl) {
        dotsEl.innerHTML = '';
        for (var i = 0; i < total; i++) {
            dotsEl.innerHTML += '<div class="hw-dot ' + (i > 0 ? 'inactive' : '') + '" id="dot' + i + '" onclick="slideOwners(' + (i - current) + ')"></div>';
        }
    }
})();

/* ── Compteur animé ───────────────────────────── */
function animateNumber(el) {
    var target = parseInt(el.getAttribute('data-target'));
    var duration = 1500;
    var start = 0;
    var step = (target / duration) * 16;
    var timer = setInterval(function() {
        start += step;
        if (start >= target) { start = target; clearInterval(timer); }
        el.textContent = Math.floor(start).toLocaleString('fr-FR');
    }, 16);
}
var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
        if (entry.isIntersecting && !entry.target.classList.contains('animated')) {
            entry.target.classList.add('animated');
            animateNumber(entry.target);
        }
    });
}, { threshold: 0.5 });
document.querySelectorAll('[data-target]').forEach(function(el) { observer.observe(el); });
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
