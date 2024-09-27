<!DOCTYPE html>
<html lang="en" data-bs-theme="">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc." />


  <!-- Light/dark mode -->
  <script>
    /*!
     * Color mode toggler for Bootstrap's docs (https://getbootstrap.com/)
     * Copyright 2011-2024 The Bootstrap Authors
     * Licensed under the Creative Commons Attribution 3.0 Unported License.
     * Modified by Simpleqode
     */
  
    (() => {
      'use strict';
  
      const getStoredTheme = () => localStorage.getItem('theme');
      const setStoredTheme = (theme) => localStorage.setItem('theme', theme);
  
      const getPreferredTheme = () => {
        const storedTheme = getStoredTheme();
        if (storedTheme) {
          return storedTheme;
        }
  
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
      };
  
      const setTheme = (theme) => {
        if (theme === 'auto') {
          document.documentElement.setAttribute('data-bs-theme', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        } else {
          document.documentElement.setAttribute('data-bs-theme', theme);
        }
      };
  
      setTheme(getPreferredTheme());
  
      const showActiveTheme = (theme, focus = false) => {
        const themeSwitchers = document.querySelectorAll('[data-bs-theme-switcher]');
  
        themeSwitchers.forEach((themeSwitcher) => {
          const themeSwitcherIcon = themeSwitcher.querySelector('.material-symbols-outlined');
          themeSwitcherIcon.innerHTML = theme === 'light' ? 'light_mode' : theme === 'dark' ? 'dark_mode' : 'contrast';
  
          if (focus) {
            themeSwitcher.focus();
          }
        });
  
        document.querySelectorAll('[data-bs-theme-value]').forEach((element) => {
          element.classList.remove('active');
          element.setAttribute('aria-pressed', 'false');
  
          if (element.getAttribute('data-bs-theme-value') === theme) {
            element.classList.add('active');
            element.setAttribute('aria-pressed', 'true');
          }
        });
      };
  
      const refreshCharts = () => {
        const charts = document.querySelectorAll('.chart-canvas');
  
        charts.forEach((chart) => {
          const chartId = chart.getAttribute('id');
          const instance = Chart.getChart(chartId);
  
          if (instance) {
            instance.options.scales.y.grid.color = getComputedStyle(document.documentElement).getPropertyValue('--bs-border-color');
            instance.options.scales.y.ticks.color = getComputedStyle(document.documentElement).getPropertyValue('--bs-secondary-color');
            instance.options.scales.x.ticks.color = getComputedStyle(document.documentElement).getPropertyValue('--bs-secondary-color');
            instance.update();
          }
        });
      };
  
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
        const storedTheme = getStoredTheme();
        if (storedTheme !== 'light' && storedTheme !== 'dark') {
          setTheme(getPreferredTheme());
        }
      });
  
      window.addEventListener('DOMContentLoaded', () => {
        showActiveTheme(getPreferredTheme());
  
        document.querySelectorAll('[data-bs-theme-value]').forEach((toggle) => {
          toggle.addEventListener('click', (e) => {
            e.preventDefault();
            const theme = toggle.getAttribute('data-bs-theme-value');
            setStoredTheme(theme);
            setTheme(theme);
            showActiveTheme(theme, true);
            refreshCharts();
          });
        });
      });
    })();
  </script>
  
  <!-- Favicon -->
  <link rel="shortcut icon" href="<?= PROOT; ?>assets/media/logo.jpeg" type="image/x-icon" />
  
  <!-- Fonts and icons -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,1,0" /> 
  
  <!-- Libs CSS -->
  <link rel="stylesheet" href="<?= PROOT; ?>assets/css/libs.bundle.css" />
  
  <!-- Theme CSS -->
  <!-- <link rel="stylesheet" type="text/css" href="<?= PROOT; ?>assets/css/main.css"> -->
  <link rel="stylesheet" href="<?= PROOT; ?>assets/css/jspence.css" />
  <link rel="stylesheet" href="<?= PROOT; ?>assets/css/theme.bundle.css" />
  
  <!-- Title -->
  <title>JSPENCE – Gold and Finance Dashboard</title>
</head>
