<?php
/** no direct access **/
defined('_WPLEXEC') or die('Restricted access');
?>
<style type="text/css">
@-ms-viewport {
  width: device-width;
}

html {
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
  -ms-overflow-style: scrollbar;
}

*,
*::before,
*::after {
  -webkit-box-sizing: inherit;
  box-sizing: inherit;
}

.hbp-clearfix::after {
  display: block;
  clear: both;
  content: '';
}

.hbp-visible {
  visibility: visible !important;
}

.hbp-invisible {
  visibility: hidden !important;
}

.hbp-hidden-xs-up {
  display: none !important;
}

@media (max-width: 575.98px) {
  .hbp-hidden-xs-down {
    display: none !important;
  }
}

@media (min-width: 576px) {
  .hbp-hidden-sm-up {
    display: none !important;
  }
}

@media (max-width: 767.98px) {
  .hbp-hidden-sm-down {
    display: none !important;
  }
}

@media (min-width: 768px) {
  .hbp-hidden-md-up {
    display: none !important;
  }
}

@media (max-width: 991.98px) {
  .hbp-hidden-md-down {
    display: none !important;
  }
}

@media (min-width: 992px) {
  .hbp-hidden-lg-up {
    display: none !important;
  }
}

@media (max-width: 1199.98px) {
  .hbp-hidden-lg-down {
    display: none !important;
  }
}

@media (min-width: 1200px) {
  .hbp-hidden-xl-up {
    display: none !important;
  }
}

.hbp-hidden-xl-down {
  display: none !important;
}

.hbp-visible-print-block {
  display: none !important;
}

@media print {
  .hbp-visible-print-block {
    display: block !important;
  }
}

.hbp-visible-print-inline {
  display: none !important;
}

@media print {
  .hbp-visible-print-inline {
    display: inline !important;
  }
}

.hbp-visible-print-inline-block {
  display: none !important;
}

@media print {
  .hbp-visible-print-inline-block {
    display: inline-block !important;
  }
}

@media print {
  .hbp-hidden-print {
    display: none !important;
  }
}

.hbp-container {
  width: 100%;
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto;
}

@media (min-width: 576px) {
  .hbp-container {
    max-width: 540px;
  }
}

@media (min-width: 768px) {
  .hbp-container {
    max-width: 720px;
  }
}

@media (min-width: 992px) {
  .hbp-container {
    max-width: 960px;
  }
}

@media (min-width: 1200px) {
  .hbp-container {
    max-width: 1140px;
  }
}

.hbp-container-fluid {
  width: 100%;
  padding-right: 15px;
  padding-left: 15px;
  margin-right: auto;
  margin-left: auto;
}

.hbp-row {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-flex-wrap: wrap;
  -ms-flex-wrap: wrap;
  flex-wrap: wrap;
  margin-right: -15px;
  margin-left: -15px;
}

.hbp-no-gutters {
  margin-right: 0;
  margin-left: 0;
}

.hbp-no-gutters > .col,
.hbp-no-gutters > [class*='col-'] {
  padding-right: 0;
  padding-left: 0;
}

.hbp-col-1,
.hbp-col-2,
.hbp-col-3,
.hbp-col-4,
.hbp-col-5,
.hbp-col-6,
.hbp-col-7,
.hbp-col-8,
.hbp-col-9,
.hbp-col-10,
.hbp-col-11,
.hbp-col-12,
.hbp-col,
.hbp-col-auto,
.hbp-col-sm-1,
.hbp-col-sm-2,
.hbp-col-sm-3,
.hbp-col-sm-4,
.hbp-col-sm-5,
.hbp-col-sm-6,
.hbp-col-sm-7,
.hbp-col-sm-8,
.hbp-col-sm-9,
.hbp-col-sm-10,
.hbp-col-sm-11,
.hbp-col-sm-12,
.hbp-col-sm,
.hbp-col-sm-auto,
.hbp-col-md-1,
.hbp-col-md-2,
.hbp-col-md-3,
.hbp-col-md-4,
.hbp-col-md-5,
.hbp-col-md-6,
.hbp-col-md-7,
.hbp-col-md-8,
.hbp-col-md-9,
.hbp-col-md-10,
.hbp-col-md-11,
.hbp-col-md-12,
.hbp-col-md,
.hbp-col-md-auto,
.hbp-col-lg-1,
.hbp-col-lg-2,
.hbp-col-lg-3,
.hbp-col-lg-4,
.hbp-col-lg-5,
.hbp-col-lg-6,
.hbp-col-lg-7,
.hbp-col-lg-8,
.hbp-col-lg-9,
.hbp-col-lg-10,
.hbp-col-lg-11,
.hbp-col-lg-12,
.hbp-col-lg,
.hbp-col-lg-auto,
.hbp-col-xl-1,
.hbp-col-xl-2,
.hbp-col-xl-3,
.hbp-col-xl-4,
.hbp-col-xl-5,
.hbp-col-xl-6,
.hbp-col-xl-7,
.hbp-col-xl-8,
.hbp-col-xl-9,
.hbp-col-xl-10,
.hbp-col-xl-11,
.hbp-col-xl-12,
.hbp-col-xl,
.hbp-col-xl-auto {
  position: relative;
  width: 100%;
  padding-right: 15px;
  padding-left: 15px;
}

.hbp-col {
  -webkit-flex-basis: 0;
  -ms-flex-preferred-size: 0;
  flex-basis: 0;
  -webkit-box-flex: 1;
  -webkit-flex-grow: 1;
  -ms-flex-positive: 1;
  flex-grow: 1;
  max-width: 100%;
}

.hbp-col-auto {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 auto;
  -ms-flex: 0 0 auto;
  flex: 0 0 auto;
  width: auto;
  max-width: 100%;
}

.hbp-col-1 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 8.3333333333%;
  -ms-flex: 0 0 8.3333333333%;
  flex: 0 0 8.3333333333%;
  max-width: 8.3333333333%;
}

.hbp-col-2 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 16.6666666667%;
  -ms-flex: 0 0 16.6666666667%;
  flex: 0 0 16.6666666667%;
  max-width: 16.6666666667%;
}

.hbp-col-3 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 25%;
  -ms-flex: 0 0 25%;
  flex: 0 0 25%;
  max-width: 25%;
}

.hbp-col-4 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 33.3333333333%;
  -ms-flex: 0 0 33.3333333333%;
  flex: 0 0 33.3333333333%;
  max-width: 33.3333333333%;
}

.hbp-col-5 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 41.6666666667%;
  -ms-flex: 0 0 41.6666666667%;
  flex: 0 0 41.6666666667%;
  max-width: 41.6666666667%;
}

.hbp-col-6 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 50%;
  -ms-flex: 0 0 50%;
  flex: 0 0 50%;
  max-width: 50%;
}

.hbp-col-7 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 58.3333333333%;
  -ms-flex: 0 0 58.3333333333%;
  flex: 0 0 58.3333333333%;
  max-width: 58.3333333333%;
}

.hbp-col-8 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 66.6666666667%;
  -ms-flex: 0 0 66.6666666667%;
  flex: 0 0 66.6666666667%;
  max-width: 66.6666666667%;
}

.hbp-col-9 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 75%;
  -ms-flex: 0 0 75%;
  flex: 0 0 75%;
  max-width: 75%;
}

.hbp-col-10 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 83.3333333333%;
  -ms-flex: 0 0 83.3333333333%;
  flex: 0 0 83.3333333333%;
  max-width: 83.3333333333%;
}

.hbp-col-11 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 91.6666666667%;
  -ms-flex: 0 0 91.6666666667%;
  flex: 0 0 91.6666666667%;
  max-width: 91.6666666667%;
}

.hbp-col-12 {
  -webkit-box-flex: 0;
  -webkit-flex: 0 0 100%;
  -ms-flex: 0 0 100%;
  flex: 0 0 100%;
  max-width: 100%;
}

.hbp-order-first {
  -webkit-box-ordinal-group: 0;
  -webkit-order: -1;
  -ms-flex-order: -1;
  order: -1;
}

.hbp-order-last {
  -webkit-box-ordinal-group: 14;
  -webkit-order: 13;
  -ms-flex-order: 13;
  order: 13;
}

.hbp-order-0 {
  -webkit-box-ordinal-group: 1;
  -webkit-order: 0;
  -ms-flex-order: 0;
  order: 0;
}

.hbp-order-1 {
  -webkit-box-ordinal-group: 2;
  -webkit-order: 1;
  -ms-flex-order: 1;
  order: 1;
}

.hbp-order-2 {
  -webkit-box-ordinal-group: 3;
  -webkit-order: 2;
  -ms-flex-order: 2;
  order: 2;
}

.hbp-order-3 {
  -webkit-box-ordinal-group: 4;
  -webkit-order: 3;
  -ms-flex-order: 3;
  order: 3;
}

.hbp-order-4 {
  -webkit-box-ordinal-group: 5;
  -webkit-order: 4;
  -ms-flex-order: 4;
  order: 4;
}

.hbp-order-5 {
  -webkit-box-ordinal-group: 6;
  -webkit-order: 5;
  -ms-flex-order: 5;
  order: 5;
}

.hbp-order-6 {
  -webkit-box-ordinal-group: 7;
  -webkit-order: 6;
  -ms-flex-order: 6;
  order: 6;
}

.hbp-order-7 {
  -webkit-box-ordinal-group: 8;
  -webkit-order: 7;
  -ms-flex-order: 7;
  order: 7;
}

.hbp-order-8 {
  -webkit-box-ordinal-group: 9;
  -webkit-order: 8;
  -ms-flex-order: 8;
  order: 8;
}

.hbp-order-9 {
  -webkit-box-ordinal-group: 10;
  -webkit-order: 9;
  -ms-flex-order: 9;
  order: 9;
}

.hbp-order-10 {
  -webkit-box-ordinal-group: 11;
  -webkit-order: 10;
  -ms-flex-order: 10;
  order: 10;
}

.hbp-order-11 {
  -webkit-box-ordinal-group: 12;
  -webkit-order: 11;
  -ms-flex-order: 11;
  order: 11;
}

.hbp-order-12 {
  -webkit-box-ordinal-group: 13;
  -webkit-order: 12;
  -ms-flex-order: 12;
  order: 12;
}

.hbp-offset-1 {
  margin-left: 8.3333333333%;
}

.hbp-offset-2 {
  margin-left: 16.6666666667%;
}

.hbp-offset-3 {
  margin-left: 25%;
}

.hbp-offset-4 {
  margin-left: 33.3333333333%;
}

.hbp-offset-5 {
  margin-left: 41.6666666667%;
}

.hbp-offset-6 {
  margin-left: 50%;
}

.hbp-offset-7 {
  margin-left: 58.3333333333%;
}

.hbp-offset-8 {
  margin-left: 66.6666666667%;
}

.hbp-offset-9 {
  margin-left: 75%;
}

.hbp-offset-10 {
  margin-left: 83.3333333333%;
}

.hbp-offset-11 {
  margin-left: 91.6666666667%;
}

@media (min-width: 576px) {
  .hbp-col-sm {
    -webkit-flex-basis: 0;
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }
  .hbp-col-sm-auto {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 auto;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: auto;
    max-width: 100%;
  }
  .hbp-col-sm-1 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 8.3333333333%;
    -ms-flex: 0 0 8.3333333333%;
    flex: 0 0 8.3333333333%;
    max-width: 8.3333333333%;
  }
  .hbp-col-sm-2 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 16.6666666667%;
    -ms-flex: 0 0 16.6666666667%;
    flex: 0 0 16.6666666667%;
    max-width: 16.6666666667%;
  }
  .hbp-col-sm-3 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 25%;
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .hbp-col-sm-4 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 33.3333333333%;
    -ms-flex: 0 0 33.3333333333%;
    flex: 0 0 33.3333333333%;
    max-width: 33.3333333333%;
  }
  .hbp-col-sm-5 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 41.6666666667%;
    -ms-flex: 0 0 41.6666666667%;
    flex: 0 0 41.6666666667%;
    max-width: 41.6666666667%;
  }
  .hbp-col-sm-6 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 50%;
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .hbp-col-sm-7 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 58.3333333333%;
    -ms-flex: 0 0 58.3333333333%;
    flex: 0 0 58.3333333333%;
    max-width: 58.3333333333%;
  }
  .hbp-col-sm-8 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 66.6666666667%;
    -ms-flex: 0 0 66.6666666667%;
    flex: 0 0 66.6666666667%;
    max-width: 66.6666666667%;
  }
  .hbp-col-sm-9 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 75%;
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .hbp-col-sm-10 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 83.3333333333%;
    -ms-flex: 0 0 83.3333333333%;
    flex: 0 0 83.3333333333%;
    max-width: 83.3333333333%;
  }
  .hbp-col-sm-11 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 91.6666666667%;
    -ms-flex: 0 0 91.6666666667%;
    flex: 0 0 91.6666666667%;
    max-width: 91.6666666667%;
  }
  .hbp-col-sm-12 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 100%;
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .hbp-order-sm-first {
    -webkit-box-ordinal-group: 0;
    -webkit-order: -1;
    -ms-flex-order: -1;
    order: -1;
  }
  .hbp-order-sm-last {
    -webkit-box-ordinal-group: 14;
    -webkit-order: 13;
    -ms-flex-order: 13;
    order: 13;
  }
  .hbp-order-sm-0 {
    -webkit-box-ordinal-group: 1;
    -webkit-order: 0;
    -ms-flex-order: 0;
    order: 0;
  }
  .hbp-order-sm-1 {
    -webkit-box-ordinal-group: 2;
    -webkit-order: 1;
    -ms-flex-order: 1;
    order: 1;
  }
  .hbp-order-sm-2 {
    -webkit-box-ordinal-group: 3;
    -webkit-order: 2;
    -ms-flex-order: 2;
    order: 2;
  }
  .hbp-order-sm-3 {
    -webkit-box-ordinal-group: 4;
    -webkit-order: 3;
    -ms-flex-order: 3;
    order: 3;
  }
  .hbp-order-sm-4 {
    -webkit-box-ordinal-group: 5;
    -webkit-order: 4;
    -ms-flex-order: 4;
    order: 4;
  }
  .hbp-order-sm-5 {
    -webkit-box-ordinal-group: 6;
    -webkit-order: 5;
    -ms-flex-order: 5;
    order: 5;
  }
  .hbp-order-sm-6 {
    -webkit-box-ordinal-group: 7;
    -webkit-order: 6;
    -ms-flex-order: 6;
    order: 6;
  }
  .hbp-order-sm-7 {
    -webkit-box-ordinal-group: 8;
    -webkit-order: 7;
    -ms-flex-order: 7;
    order: 7;
  }
  .hbp-order-sm-8 {
    -webkit-box-ordinal-group: 9;
    -webkit-order: 8;
    -ms-flex-order: 8;
    order: 8;
  }
  .hbp-order-sm-9 {
    -webkit-box-ordinal-group: 10;
    -webkit-order: 9;
    -ms-flex-order: 9;
    order: 9;
  }
  .hbp-order-sm-10 {
    -webkit-box-ordinal-group: 11;
    -webkit-order: 10;
    -ms-flex-order: 10;
    order: 10;
  }
  .hbp-order-sm-11 {
    -webkit-box-ordinal-group: 12;
    -webkit-order: 11;
    -ms-flex-order: 11;
    order: 11;
  }
  .hbp-order-sm-12 {
    -webkit-box-ordinal-group: 13;
    -webkit-order: 12;
    -ms-flex-order: 12;
    order: 12;
  }
  .hbp-offset-sm-0 {
    margin-left: 0;
  }
  .hbp-offset-sm-1 {
    margin-left: 8.3333333333%;
  }
  .hbp-offset-sm-2 {
    margin-left: 16.6666666667%;
  }
  .hbp-offset-sm-3 {
    margin-left: 25%;
  }
  .hbp-offset-sm-4 {
    margin-left: 33.3333333333%;
  }
  .hbp-offset-sm-5 {
    margin-left: 41.6666666667%;
  }
  .hbp-offset-sm-6 {
    margin-left: 50%;
  }
  .hbp-offset-sm-7 {
    margin-left: 58.3333333333%;
  }
  .hbp-offset-sm-8 {
    margin-left: 66.6666666667%;
  }
  .hbp-offset-sm-9 {
    margin-left: 75%;
  }
  .hbp-offset-sm-10 {
    margin-left: 83.3333333333%;
  }
  .hbp-offset-sm-11 {
    margin-left: 91.6666666667%;
  }
}

@media (min-width: 768px) {
  .hbp-col-md {
    -webkit-flex-basis: 0;
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }
  .hbp-col-md-auto {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 auto;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: auto;
    max-width: 100%;
  }
  .hbp-col-md-1 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 8.3333333333%;
    -ms-flex: 0 0 8.3333333333%;
    flex: 0 0 8.3333333333%;
    max-width: 8.3333333333%;
  }
  .hbp-col-md-2 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 16.6666666667%;
    -ms-flex: 0 0 16.6666666667%;
    flex: 0 0 16.6666666667%;
    max-width: 16.6666666667%;
  }
  .hbp-col-md-3 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 25%;
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .hbp-col-md-4 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 33.3333333333%;
    -ms-flex: 0 0 33.3333333333%;
    flex: 0 0 33.3333333333%;
    max-width: 33.3333333333%;
  }
  .hbp-col-md-5 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 41.6666666667%;
    -ms-flex: 0 0 41.6666666667%;
    flex: 0 0 41.6666666667%;
    max-width: 41.6666666667%;
  }
  .hbp-col-md-6 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 50%;
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .hbp-col-md-7 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 58.3333333333%;
    -ms-flex: 0 0 58.3333333333%;
    flex: 0 0 58.3333333333%;
    max-width: 58.3333333333%;
  }
  .hbp-col-md-8 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 66.6666666667%;
    -ms-flex: 0 0 66.6666666667%;
    flex: 0 0 66.6666666667%;
    max-width: 66.6666666667%;
  }
  .hbp-col-md-9 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 75%;
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .hbp-col-md-10 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 83.3333333333%;
    -ms-flex: 0 0 83.3333333333%;
    flex: 0 0 83.3333333333%;
    max-width: 83.3333333333%;
  }
  .hbp-col-md-11 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 91.6666666667%;
    -ms-flex: 0 0 91.6666666667%;
    flex: 0 0 91.6666666667%;
    max-width: 91.6666666667%;
  }
  .hbp-col-md-12 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 100%;
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .hbp-order-md-first {
    -webkit-box-ordinal-group: 0;
    -webkit-order: -1;
    -ms-flex-order: -1;
    order: -1;
  }
  .hbp-order-md-last {
    -webkit-box-ordinal-group: 14;
    -webkit-order: 13;
    -ms-flex-order: 13;
    order: 13;
  }
  .hbp-order-md-0 {
    -webkit-box-ordinal-group: 1;
    -webkit-order: 0;
    -ms-flex-order: 0;
    order: 0;
  }
  .hbp-order-md-1 {
    -webkit-box-ordinal-group: 2;
    -webkit-order: 1;
    -ms-flex-order: 1;
    order: 1;
  }
  .hbp-order-md-2 {
    -webkit-box-ordinal-group: 3;
    -webkit-order: 2;
    -ms-flex-order: 2;
    order: 2;
  }
  .hbp-order-md-3 {
    -webkit-box-ordinal-group: 4;
    -webkit-order: 3;
    -ms-flex-order: 3;
    order: 3;
  }
  .hbp-order-md-4 {
    -webkit-box-ordinal-group: 5;
    -webkit-order: 4;
    -ms-flex-order: 4;
    order: 4;
  }
  .hbp-order-md-5 {
    -webkit-box-ordinal-group: 6;
    -webkit-order: 5;
    -ms-flex-order: 5;
    order: 5;
  }
  .hbp-order-md-6 {
    -webkit-box-ordinal-group: 7;
    -webkit-order: 6;
    -ms-flex-order: 6;
    order: 6;
  }
  .hbp-order-md-7 {
    -webkit-box-ordinal-group: 8;
    -webkit-order: 7;
    -ms-flex-order: 7;
    order: 7;
  }
  .hbp-order-md-8 {
    -webkit-box-ordinal-group: 9;
    -webkit-order: 8;
    -ms-flex-order: 8;
    order: 8;
  }
  .hbp-order-md-9 {
    -webkit-box-ordinal-group: 10;
    -webkit-order: 9;
    -ms-flex-order: 9;
    order: 9;
  }
  .hbp-order-md-10 {
    -webkit-box-ordinal-group: 11;
    -webkit-order: 10;
    -ms-flex-order: 10;
    order: 10;
  }
  .hbp-order-md-11 {
    -webkit-box-ordinal-group: 12;
    -webkit-order: 11;
    -ms-flex-order: 11;
    order: 11;
  }
  .hbp-order-md-12 {
    -webkit-box-ordinal-group: 13;
    -webkit-order: 12;
    -ms-flex-order: 12;
    order: 12;
  }
  .hbp-offset-md-0 {
    margin-left: 0;
  }
  .hbp-offset-md-1 {
    margin-left: 8.3333333333%;
  }
  .hbp-offset-md-2 {
    margin-left: 16.6666666667%;
  }
  .hbp-offset-md-3 {
    margin-left: 25%;
  }
  .hbp-offset-md-4 {
    margin-left: 33.3333333333%;
  }
  .hbp-offset-md-5 {
    margin-left: 41.6666666667%;
  }
  .hbp-offset-md-6 {
    margin-left: 50%;
  }
  .hbp-offset-md-7 {
    margin-left: 58.3333333333%;
  }
  .hbp-offset-md-8 {
    margin-left: 66.6666666667%;
  }
  .hbp-offset-md-9 {
    margin-left: 75%;
  }
  .hbp-offset-md-10 {
    margin-left: 83.3333333333%;
  }
  .hbp-offset-md-11 {
    margin-left: 91.6666666667%;
  }
}

@media (min-width: 992px) {
  .hbp-col-lg {
    -webkit-flex-basis: 0;
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }
  .hbp-col-lg-auto {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 auto;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: auto;
    max-width: 100%;
  }
  .hbp-col-lg-1 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 8.3333333333%;
    -ms-flex: 0 0 8.3333333333%;
    flex: 0 0 8.3333333333%;
    max-width: 8.3333333333%;
  }
  .hbp-col-lg-2 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 16.6666666667%;
    -ms-flex: 0 0 16.6666666667%;
    flex: 0 0 16.6666666667%;
    max-width: 16.6666666667%;
  }
  .hbp-col-lg-3 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 25%;
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .hbp-col-lg-4 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 33.3333333333%;
    -ms-flex: 0 0 33.3333333333%;
    flex: 0 0 33.3333333333%;
    max-width: 33.3333333333%;
  }
  .hbp-col-lg-5 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 41.6666666667%;
    -ms-flex: 0 0 41.6666666667%;
    flex: 0 0 41.6666666667%;
    max-width: 41.6666666667%;
  }
  .hbp-col-lg-6 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 50%;
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .hbp-col-lg-7 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 58.3333333333%;
    -ms-flex: 0 0 58.3333333333%;
    flex: 0 0 58.3333333333%;
    max-width: 58.3333333333%;
  }
  .hbp-col-lg-8 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 66.6666666667%;
    -ms-flex: 0 0 66.6666666667%;
    flex: 0 0 66.6666666667%;
    max-width: 66.6666666667%;
  }
  .hbp-col-lg-9 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 75%;
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .hbp-col-lg-10 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 83.3333333333%;
    -ms-flex: 0 0 83.3333333333%;
    flex: 0 0 83.3333333333%;
    max-width: 83.3333333333%;
  }
  .hbp-col-lg-11 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 91.6666666667%;
    -ms-flex: 0 0 91.6666666667%;
    flex: 0 0 91.6666666667%;
    max-width: 91.6666666667%;
  }
  .hbp-col-lg-12 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 100%;
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .hbp-order-lg-first {
    -webkit-box-ordinal-group: 0;
    -webkit-order: -1;
    -ms-flex-order: -1;
    order: -1;
  }
  .hbp-order-lg-last {
    -webkit-box-ordinal-group: 14;
    -webkit-order: 13;
    -ms-flex-order: 13;
    order: 13;
  }
  .hbp-order-lg-0 {
    -webkit-box-ordinal-group: 1;
    -webkit-order: 0;
    -ms-flex-order: 0;
    order: 0;
  }
  .hbp-order-lg-1 {
    -webkit-box-ordinal-group: 2;
    -webkit-order: 1;
    -ms-flex-order: 1;
    order: 1;
  }
  .hbp-order-lg-2 {
    -webkit-box-ordinal-group: 3;
    -webkit-order: 2;
    -ms-flex-order: 2;
    order: 2;
  }
  .hbp-order-lg-3 {
    -webkit-box-ordinal-group: 4;
    -webkit-order: 3;
    -ms-flex-order: 3;
    order: 3;
  }
  .hbp-order-lg-4 {
    -webkit-box-ordinal-group: 5;
    -webkit-order: 4;
    -ms-flex-order: 4;
    order: 4;
  }
  .hbp-order-lg-5 {
    -webkit-box-ordinal-group: 6;
    -webkit-order: 5;
    -ms-flex-order: 5;
    order: 5;
  }
  .hbp-order-lg-6 {
    -webkit-box-ordinal-group: 7;
    -webkit-order: 6;
    -ms-flex-order: 6;
    order: 6;
  }
  .hbp-order-lg-7 {
    -webkit-box-ordinal-group: 8;
    -webkit-order: 7;
    -ms-flex-order: 7;
    order: 7;
  }
  .hbp-order-lg-8 {
    -webkit-box-ordinal-group: 9;
    -webkit-order: 8;
    -ms-flex-order: 8;
    order: 8;
  }
  .hbp-order-lg-9 {
    -webkit-box-ordinal-group: 10;
    -webkit-order: 9;
    -ms-flex-order: 9;
    order: 9;
  }
  .hbp-order-lg-10 {
    -webkit-box-ordinal-group: 11;
    -webkit-order: 10;
    -ms-flex-order: 10;
    order: 10;
  }
  .hbp-order-lg-11 {
    -webkit-box-ordinal-group: 12;
    -webkit-order: 11;
    -ms-flex-order: 11;
    order: 11;
  }
  .hbp-order-lg-12 {
    -webkit-box-ordinal-group: 13;
    -webkit-order: 12;
    -ms-flex-order: 12;
    order: 12;
  }
  .hbp-offset-lg-0 {
    margin-left: 0;
  }
  .hbp-offset-lg-1 {
    margin-left: 8.3333333333%;
  }
  .hbp-offset-lg-2 {
    margin-left: 16.6666666667%;
  }
  .hbp-offset-lg-3 {
    margin-left: 25%;
  }
  .hbp-offset-lg-4 {
    margin-left: 33.3333333333%;
  }
  .hbp-offset-lg-5 {
    margin-left: 41.6666666667%;
  }
  .hbp-offset-lg-6 {
    margin-left: 50%;
  }
  .hbp-offset-lg-7 {
    margin-left: 58.3333333333%;
  }
  .hbp-offset-lg-8 {
    margin-left: 66.6666666667%;
  }
  .hbp-offset-lg-9 {
    margin-left: 75%;
  }
  .hbp-offset-lg-10 {
    margin-left: 83.3333333333%;
  }
  .hbp-offset-lg-11 {
    margin-left: 91.6666666667%;
  }
}

@media (min-width: 1200px) {
  .hbp-col-xl {
    -webkit-flex-basis: 0;
    -ms-flex-preferred-size: 0;
    flex-basis: 0;
    -webkit-box-flex: 1;
    -webkit-flex-grow: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    max-width: 100%;
  }
  .hbp-col-xl-auto {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 auto;
    -ms-flex: 0 0 auto;
    flex: 0 0 auto;
    width: auto;
    max-width: 100%;
  }
  .hbp-col-xl-1 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 8.3333333333%;
    -ms-flex: 0 0 8.3333333333%;
    flex: 0 0 8.3333333333%;
    max-width: 8.3333333333%;
  }
  .hbp-col-xl-2 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 16.6666666667%;
    -ms-flex: 0 0 16.6666666667%;
    flex: 0 0 16.6666666667%;
    max-width: 16.6666666667%;
  }
  .hbp-col-xl-3 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 25%;
    -ms-flex: 0 0 25%;
    flex: 0 0 25%;
    max-width: 25%;
  }
  .hbp-col-xl-4 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 33.3333333333%;
    -ms-flex: 0 0 33.3333333333%;
    flex: 0 0 33.3333333333%;
    max-width: 33.3333333333%;
  }
  .hbp-col-xl-5 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 41.6666666667%;
    -ms-flex: 0 0 41.6666666667%;
    flex: 0 0 41.6666666667%;
    max-width: 41.6666666667%;
  }
  .hbp-col-xl-6 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 50%;
    -ms-flex: 0 0 50%;
    flex: 0 0 50%;
    max-width: 50%;
  }
  .hbp-col-xl-7 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 58.3333333333%;
    -ms-flex: 0 0 58.3333333333%;
    flex: 0 0 58.3333333333%;
    max-width: 58.3333333333%;
  }
  .hbp-col-xl-8 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 66.6666666667%;
    -ms-flex: 0 0 66.6666666667%;
    flex: 0 0 66.6666666667%;
    max-width: 66.6666666667%;
  }
  .hbp-col-xl-9 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 75%;
    -ms-flex: 0 0 75%;
    flex: 0 0 75%;
    max-width: 75%;
  }
  .hbp-col-xl-10 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 83.3333333333%;
    -ms-flex: 0 0 83.3333333333%;
    flex: 0 0 83.3333333333%;
    max-width: 83.3333333333%;
  }
  .hbp-col-xl-11 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 91.6666666667%;
    -ms-flex: 0 0 91.6666666667%;
    flex: 0 0 91.6666666667%;
    max-width: 91.6666666667%;
  }
  .hbp-col-xl-12 {
    -webkit-box-flex: 0;
    -webkit-flex: 0 0 100%;
    -ms-flex: 0 0 100%;
    flex: 0 0 100%;
    max-width: 100%;
  }
  .hbp-order-xl-first {
    -webkit-box-ordinal-group: 0;
    -webkit-order: -1;
    -ms-flex-order: -1;
    order: -1;
  }
  .hbp-order-xl-last {
    -webkit-box-ordinal-group: 14;
    -webkit-order: 13;
    -ms-flex-order: 13;
    order: 13;
  }
  .hbp-order-xl-0 {
    -webkit-box-ordinal-group: 1;
    -webkit-order: 0;
    -ms-flex-order: 0;
    order: 0;
  }
  .hbp-order-xl-1 {
    -webkit-box-ordinal-group: 2;
    -webkit-order: 1;
    -ms-flex-order: 1;
    order: 1;
  }
  .hbp-order-xl-2 {
    -webkit-box-ordinal-group: 3;
    -webkit-order: 2;
    -ms-flex-order: 2;
    order: 2;
  }
  .hbp-order-xl-3 {
    -webkit-box-ordinal-group: 4;
    -webkit-order: 3;
    -ms-flex-order: 3;
    order: 3;
  }
  .hbp-order-xl-4 {
    -webkit-box-ordinal-group: 5;
    -webkit-order: 4;
    -ms-flex-order: 4;
    order: 4;
  }
  .hbp-order-xl-5 {
    -webkit-box-ordinal-group: 6;
    -webkit-order: 5;
    -ms-flex-order: 5;
    order: 5;
  }
  .hbp-order-xl-6 {
    -webkit-box-ordinal-group: 7;
    -webkit-order: 6;
    -ms-flex-order: 6;
    order: 6;
  }
  .hbp-order-xl-7 {
    -webkit-box-ordinal-group: 8;
    -webkit-order: 7;
    -ms-flex-order: 7;
    order: 7;
  }
  .hbp-order-xl-8 {
    -webkit-box-ordinal-group: 9;
    -webkit-order: 8;
    -ms-flex-order: 8;
    order: 8;
  }
  .hbp-order-xl-9 {
    -webkit-box-ordinal-group: 10;
    -webkit-order: 9;
    -ms-flex-order: 9;
    order: 9;
  }
  .hbp-order-xl-10 {
    -webkit-box-ordinal-group: 11;
    -webkit-order: 10;
    -ms-flex-order: 10;
    order: 10;
  }
  .hbp-order-xl-11 {
    -webkit-box-ordinal-group: 12;
    -webkit-order: 11;
    -ms-flex-order: 11;
    order: 11;
  }
  .hbp-order-xl-12 {
    -webkit-box-ordinal-group: 13;
    -webkit-order: 12;
    -ms-flex-order: 12;
    order: 12;
  }
  .hbp-offset-xl-0 {
    margin-left: 0;
  }
  .hbp-offset-xl-1 {
    margin-left: 8.3333333333%;
  }
  .hbp-offset-xl-2 {
    margin-left: 16.6666666667%;
  }
  .hbp-offset-xl-3 {
    margin-left: 25%;
  }
  .hbp-offset-xl-4 {
    margin-left: 33.3333333333%;
  }
  .hbp-offset-xl-5 {
    margin-left: 41.6666666667%;
  }
  .hbp-offset-xl-6 {
    margin-left: 50%;
  }
  .hbp-offset-xl-7 {
    margin-left: 58.3333333333%;
  }
  .hbp-offset-xl-8 {
    margin-left: 66.6666666667%;
  }
  .hbp-offset-xl-9 {
    margin-left: 75%;
  }
  .hbp-offset-xl-10 {
    margin-left: 83.3333333333%;
  }
  .hbp-offset-xl-11 {
    margin-left: 91.6666666667%;
  }
}

.hbp-img-fluid {
  max-width: 100%;
  height: auto;
}
/*-------------------------------------- 1 Default Styles --------------------------------------*/
html {
  min-height: 100%;
}
body {
  height: 100%;
  background-image: linear-gradient(
    241deg,
    #7c86cb,
    #313978 28%,
    #040c31 70%,
    #050f38
  );
  color: #ffffff;
  font-family: Montserrat;
  overflow-x: hidden;
}
.hbp-fw-300 {
  font-weight: 300;
}
.hbp-text-center {
  text-align: center;
}
/*-------------------------------------- 2.1 Header --------------------------------------*/
.hbp-navbar {
  background: transparent;
  margin-bottom: 78.5px;
  padding: 15px 0 0;
}
.hbp-navbar .hbp-row {
  justify-content: space-around;
}
.hbp-navbar-brand {
  text-decoration: none;
}
.hbp-navbar-brand > span {
  text-decoration: none;
  text-align: left;
  font-size: 16px;
  line-height: 21px;
  letter-spacing: -0.48px;
  display: inline-block;
  margin: 5px 0 5px 15px;
  color: #ffffff;
  font-weight: 600;
  vertical-align: top;
}
.hbp-navbar-item {
  margin: 0;
  padding: 0;
}
.hbp-navbar-item li {
  display: block;
  float: left;
  margin-right: 30px;
}
.hbp-navbar-item li:last-child {
  margin-right: 0;
}
.hbp-navbar-item li a {
  display: block;
  padding: 15px 0;
  font-size: 16px;
  font-weight: 500;
  font-stretch: normal;
  font-style: normal;
  line-height: normal;
  letter-spacing: normal;
  text-align: right;
  color: #fff;
  text-decoration: none;
}
/*-------------------------------------- 2.2 Main --------------------------------------*/
.hbp-title h1 {
  color: #fff;
  font-size: 54px;
  font-weight: 600;
  font-stretch: normal;
  font-style: normal;
  line-height: normal;
  letter-spacing: -1.62px;
  margin: 0;
}
.hbp-title p {
  font-size: 28px;
  font-weight: 300;
  font-stretch: normal;
  font-style: normal;
  line-height: 1.96;
  letter-spacing: normal;
  text-align: center;
  margin: 0;
}
.hbp-start-button {
  position: relative;
  padding: 35px 0;
  overflow: hidden;
}
.hbp-start-button a {
  z-index: 1;
  color: #fff;
  text-decoration: none;
  display: block;
  margin: 0 auto;
  text-align: center;
  font-size: 24px;
  font-weight: 300;
  box-sizing: border-box;
  position: relative;
  left: 0;
  padding: 0;
  width: 150px;
  height: 150px;
  line-height: 150px;
  border-radius: 180px;
  border-width: 0;
  background-image: linear-gradient(100deg, #040c31e0, #050f38b0),
    linear-gradient(to bottom, #7c86cb, #1fa4e9);
}
.hbp-start-button a .hbp-start-border {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 100%;
  box-sizing: border-box;
  border: 3px transparent solid;
  background-origin: border-box;
  background-clip: content-box, border-box;
  background-image: linear-gradient(100deg, #040c31e0, #050f38b0),
    linear-gradient(to bottom, #7c86cb, #1fa4e9);
}
.hbp-start-button a .hbp-start-ring {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 100%;
  box-sizing: border-box;
  border: 1px #ffffff solid;
  opacity: 0;
  animation-name: hbp-start-ring;
  animation-delay: 3.5s;
  animation-duration: 3.5s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}

.hbp-start-button a .hbp-start-ring-high {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 100%;
  box-sizing: border-box;
  border: 1px #ffffff solid;
  opacity: 0;
  animation-name: hbp-start-ring;
  animation-duration: 1s;
  animation-iteration-count: infinite;
  animation-timing-function: linear;
}
.hbp-start-button a:focus .hbp-start-ring,
.hbp-start-button a:hover .hbp-start-ring {
  animation-name: none;
}
.hbp-progress-bar {
  background-image: url(data:image/png;base64,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);
  background-size: cover;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 100%;
  box-sizing: border-box;
  z-index: -1;
}
.hbp-start-button a .hbp-start-text {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 100%;
  box-sizing: border-box;
  white-space: nowrap;
  font-size: 24px;
  font-weight: 300;
}
.hbp-mbps-text {
  display: block;
  position: absolute;
  top: 25px;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 100%;
  box-sizing: border-box;
  white-space: nowrap;
  font-size: 10px;
  font-weight: bold;
}
.hbp-running {
  margin-bottom: 35px;
  font-size: 22px;
  font-weight: 300;
}

.hbp-running a {
  color:white;
  text-decoration: none;
}

.hbp-download {
  display: inline-block;
  position: relative;
  color: #ffffff;
  text-decoration: none;
  font-size: 17px;
  font-weight: 500;
  border-top: 1px solid #fff;
  padding: 6px 24px 6px 24px;
}
.hbp-download::before {
  content: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAOBAMAAADUAYG5AAAAJFBMVEVHcEz///////////////////////////////////////////8Uel1nAAAAC3RSTlMAMD/wQBiEv6CvYOCkHoEAAABRSURBVAjXY2BgYJDeyAAB3lswGJsCwHRz9jZzEItxNxAIgERW795tCJZitN4sAFEsDBZoF2BgDWBgrGDYnQDisu1m2D1JCQg0gQwoYLCG0JsBS5kd9yUH40IAAAAASUVORK5CYII=);
  position: absolute;
  top: 6px;
  z-index: 1000;
  left: 18px;
}
.hbp-download span {
  margin-left: 18px;
}
.hbp-top-shave {
  position: absolute;
  z-index: -1;
  background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAzQAAAD6AgMAAAAKFuq9AAAACVBMVEVkS8NHcExhR8Ky5IQSAAAAA3RSTlMzABm4MDfXAAAFi0lEQVR42t3bv24bORDHcWkBA5F790EAAac8xT4CCw5BpGIV4PIUwlUH9yySKjCgQ05PGcWW9XeXHHK5u/PjQqWbD747HMqGF19Pz4+v3//78ffrZ//t//23feHn+df+Zf/8/NfL8fOp5/n47z8fPh4+29dnkfAsF0k/LvzZLVw9mIYWth7NmhamojS0oG09aQ4aV0+ag0bVk+ag0fWkOWionjR/NG0VafxRU8XgbOioqWJ/+ndNDfvzgd41FezPhs4a/P25utDYSqbmTWNqSfOqgR8cf6VpK0nzplGVpHnT6ErSvGmwL567Ww3y4DzSrcbVkeaosXWkOWqA9+fTvQZ3f16ledfADs66S4M6OA11aXQVad41VEWak6atIc1Jo2pIc9LoGtKcNFRDmrNmW0Gas8ZVkOassRWkOWvgLp6ND2jgjoEHCmnQ9icFNQo/zYVGw0/NpQZrcFYU0UANjo9pFHyaS42FT3OpMfBpLjVAF0/P0Dj0NFcai57mSqPR01xpCD3NtaYFT3OtwdifO6YGYnAaYmogBmfN1gDszyWxNQ47zY3GQk/NrcZgp7nRiB+ccJpbjYNOc6tR0GluNcL350OaRvb+bHyiRvTFc0OJGtGD41M1kgdnRakag5zmTiN4f8bT3GsccJp7jQVOc68xwGnuNVIHh5OmQ9PipunQKNw0HRqNm6ZDQ7hpujQtbJoujYNN06WxsGm6NPL25yPla+Ttz6chGmmDs6QhGoWaplMjbH82NEgjbH+uB2pa0DTdGgWapltjQdN0awxomm6NoP2ZlKZH4zDT9GgsZpoejcFM06ORMjiJafo0LWSaPo2CTNOnkXHx3BTSiLh4Rv8wyNZIGJwVldJI2J++mMYipunVGMQ0vZr592dGmn6NA0zTr7GAafo1BjBNv4YA0wQ0LV6agEbhpQloNF6agIbw0oQ0LVyakGa+/flI5TXz7c/dCJrZ9ueSRtDMdvFcj6KZ6RhoaBSNQksT1Gi0NEENoaUJa1qwNGGNAksT1liwNGENgaWJaLZYaSIah5UmorFYaSIag5Umopl4cIamiWkcVJqYRkGliWkmvXhuxtZMuj9pdM2EF8/V+BqFlCaq0Uhpoprp9qefQDPZ/iyRJq5xQGniGguUJq4xQGnimmkGp0wahqbFScPQKJw0DI3GScPQEE4ajmYLk4ajcTBpOBoLk4ajMTBpOJqxB+dpWs24g7OkaTXj7s/1xJpR92dDE2sIJQ1P04Kk4WkUSBqeRoOk4WkIJA1T02KkYWoURhqmRmOkYWoMRhqmZpyLZ/E0XI2DSMPVWIg0XI2BSMPVjDA4I6RhaxxCGrbGIqRhawxCGram9OCMkoavaQHS8DWFB4fm1WiANHxN0W9s6f+dXlpTcuM80Nyakt/Y/OwaIz9Ngobkp0nRFBucFQnQWPFpUjRafJoUTamrmpehcdLTJGms9DRJGiM9TZKmxOA0XozGCU+TptGypyZRQ8LTJGpa2WkSNUp2mkSNlp0mUTPwjB47TarGiU6TqrGi06RqjOg0qZohgzN+mmSNkpwmWaMlp0nW5L9qXqLGCU6TrrGC06RrjOA06ZrMe7QXqrFy02RojNw0GZqcM3qiNDkaJTZNjsaITZOjSX/VvGSNkpomS2OkpsnSJL5q06XJ0yihafI0RmiaPE3S1wIvXmNlpsnUJJwDHkDjRKbJ1RiRaXI13Fdt2jTZGisxTbaGJKbJ1ziBafI1RmCafA3ndzceR2PlpRmgiR7SjUfSxOKsCUkTidMQlsZKSzNIQz8DmEdC02hJp/NgTeBCsCE8jZF0BAzW9B4EHlLT866tCVPTuXQ2hKoxEu5nxTT0RcSmKaW55cyIKaGhL1sJM1NKQ/TrFGZH+Boy+5fDyvw8r4XoN4+MvRlYcl7xAAAAAElFTkSuQmCC);
  background-repeat: no-repeat;
  background-position: left top;
  background-size: cover;
  top: 0;
  left: 92px;
  width: 585px;
  height: 178px;
}
.hbp-bottom-shave {
  position: fixed;
  z-index: -1;
  background-image: url(data:image/png;base64,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);
  background-repeat: no-repeat;
  background-position: right bottom;
  background-size: cover;
  bottom: 0;
  right: 0;
  width: 340px;
  height: 340px;
}

.hbp-result {
 padding-top: 35px;
}

/*-------------------------------------- 2.3 Results --------------------------------------*/
.hbp-result h2,
.hbp-result h3,
.hbp-result h4 {
  margin: 0;
}
.hbp-result h2 {
  font-size: 30px;
  font-weight: 600;
  line-height: 1.83;
}
.hbp-result h4 {
  display: inline-block;
  padding: 10px 17px;
}
.hbp-result span {
  display: block;
}
.hbp-result .hbp-speed-text {
  color: #fff;
  font-size: 16px;
  font-weight: 300;
}
.hbp-result .hbp-measure-text {
  color: #fff;
  font-size: 32px;
  font-weight: 300;
}
.hbp-result .hbp-unit-text {
  color: #fff;
  font-size: 12px;
  font-weight: bold;
}
.hbp-result h4 {
  margin: 17.5px auto 0 auto;
  font-size: 16px;
  font-weight: 300;
  line-height: 1.56;
}
.hbp-network-test,
.hbp-disk-test,
.hbp-cpu-test,
.hbp-db-test {
  padding: 35px 0;
}
.hbp-network-test h2 {
  color: #61f6b4;
}
.hbp-network-test .hbp-network-icon {
  margin-bottom: 14px;
}
.hbp-disk-test h2 {
  color: #8ecaff;
}
.hbp-cpu-test h2 {
  color: #e097ff;
}
.hbp-cpu-test .hbp-cpu-icon {
  margin-bottom: 3px;
}
.hbp-cpu-test h4 {
  background-color: #ffffff1a;
  display:block;
}
.hbp-cpu-test .hbp-sub-text {
  font-size: 12px;
}
.hbp-cpu-test .hbp-load-avg {
  border-collapse: collapse;
  margin-top: 15px;
  display: inline-block;
}
.hbp-cpu-test .hbp-load-avg td {
  padding: 3px 5px;
  text-align: justify;
}

.hbp-db-test h2 {
    color: #fc0;
}
.hbp-db-test h4 {
    background-color: #ffffff1a;
    display:block;
}

/*-------------------------------------- 3.1 Animation --------------------------------------*/
@keyframes hbp-start-ring {
  0% {
    opacity: 0;
    transform: scale(1);
  }
  12.5% {
    opacity: 0;
    transform: scale(0.995);
  }
  16.667% {
    opacity: 1;
  }
  50% {
    opacity: 0;
    transform: scale(1.3);
  }
}
/*-------------------------------------- 4.1 Responsive --------------------------------------*/
@media (max-width: 767.98px) {
  .hbp-top-shave,
  .hbp-bottom-shave {
    display: none;
  }
  .hbp-start-button a .hbp-start-ring {
    animation: none;
  }
}

@media (max-width: 480px) {
    .hbp-title h1 {
      font-size: 38px;
    }
    
    .hbp-title p {
      font-size: 20px;
      line-height: 1.5;
      margin-top: 25px;
    }
    .hbp-navbar-item li {
      display: block;
      float: left;
      margin-right: 15px;
    }
}
</style>