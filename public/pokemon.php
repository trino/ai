<!doctype html>
<html>
<head>
    <title>Pokedex.org</title>
    <meta name=description content="A mini-encyclopedia of Pokémon species, types, evolutions, and moves.">
    <meta charset=utf-8>
    <meta http-equiv=X-UA-Compatible content="IE=edge">
    <meta name=viewport content="width=device-width,initial-scale=1">
    <meta name=theme-color content=#a040a0>
    <meta name=apple-mobile-web-app-capable content=yes>
    <style>/*! normalize.css v3.0.2 | MIT License | git.io/normalize */
        hr, img {
            border: 0
        }

        body, figure {
            margin: 0
        }

        .mui-btn, .mui-tabs__bar > li > a {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none
        }

        .mui-btn, .mui-btn--fab, sub, sup {
            position: relative
        }

        .mui--clearfix:after, .mui-container-fluid:after, .mui-container:after, .mui-panel:after, .mui-row:after {
            clear: both
        }

        html {
            font-family: sans-serif;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%
        }

        article, aside, details, figcaption, figure, footer, header, hgroup, main, menu, nav, section, summary {
            display: block
        }

        audio, canvas, progress, video {
            display: inline-block;
            vertical-align: baseline
        }

        audio:not([controls]) {
            display: none;
            height: 0
        }

        [hidden], template {
            display: none
        }

        .mui-container-fluid:after, .mui-container-fluid:before, .mui-container:after, .mui-container:before, .mui-panel:after, .mui-panel:before, .mui-row:after, .mui-row:before {
            display: table;
            content: " "
        }

        a {
            background-color: transparent;
            color: #2196F3;
            text-decoration: none
        }

        a:active, a:hover {
            outline: 0
        }

        b, optgroup, strong {
            font-weight: 700
        }

        dfn {
            font-style: italic
        }

        h1 {
            margin: .67em 0
        }

        mark {
            background: #ff0;
            color: #000
        }

        .mui-btn, .mui-textfield > input, .mui-textfield > textarea {
            -webkit-animation-duration: .1ms;
            -webkit-animation-name: mui-node-inserted;
            background-image: none
        }

        small {
            font-size: 80%
        }

        sub, sup {
            font-size: 75%;
            line-height: 0;
            vertical-align: baseline
        }

        .mui-btn, img {
            vertical-align: middle
        }

        sup {
            top: -.5em
        }

        sub {
            bottom: -.25em
        }

        svg:not(:root) {
            overflow: hidden
        }

        hr {
            box-sizing: content-box;
            margin-top: 20px;
            margin-bottom: 20px;
            height: 1px;
            background-color: rgba(0, 0, 0, .12)
        }

        .mui-btn, .mui-panel, body {
            background-color: #FFF
        }

        pre, textarea {
            overflow: auto
        }

        code, kbd, pre, samp {
            font-family: monospace, monospace;
            font-size: 1em
        }

        button, input, optgroup, select, textarea {
            color: inherit;
            font: inherit;
            margin: 0
        }

        button {
            overflow: visible
        }

        button, select {
            text-transform: none
        }

        button, html input[type=button], input[type=reset], input[type=submit] {
            -webkit-appearance: button;
            cursor: pointer
        }

        button[disabled], html input[disabled] {
            cursor: default
        }

        button::-moz-focus-inner, input::-moz-focus-inner {
            border: 0;
            padding: 0
        }

        input[type=checkbox], input[type=radio] {
            box-sizing: border-box;
            padding: 0
        }

        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button {
            height: auto
        }

        input[type=search]::-webkit-search-cancel-button, input[type=search]::-webkit-search-decoration {
            -webkit-appearance: none
        }

        fieldset {
            border: 1px solid silver;
            margin: 0 2px;
            padding: .35em .625em .75em
        }

        table {
            border-collapse: collapse;
            border-spacing: 0
        }

        td, th {
            padding: 0
        }

        *, :after, :before {
            box-sizing: border-box
        }

        html {
            font-size: 10px;
            -webkit-tap-highlight-color: transparent
        }

        body {
            font-family: "Helvetica Neue", Helvetica, Arial, Verdana, "Trebuchet MS";
            font-size: 14px;
            font-weight: 400;
            line-height: 1.429;
            color: rgba(0, 0, 0, .87)
        }

        button, input, select, textarea {
            font-family: inherit;
            font-size: inherit;
            line-height: inherit
        }

        a:focus, a:hover {
            color: #1976D2;
            text-decoration: underline
        }

        a:focus {
            outline: dotted thin;
            outline: -webkit-focus-ring-color auto 5px;
            outline-offset: -2px
        }

        p {
            margin: 0 0 10px
        }

        ol, ul {
            margin-top: 0;
            margin-bottom: 10px
        }

        .mui-container, .mui-container-fluid {
            margin-right: auto;
            margin-left: auto;
            padding-left: 15px;
            padding-right: 15px
        }

        @media (min-width: 768px) {
            .mui-container {
                width: 750px
            }
        }

        @media (min-width: 992px) {
            .mui-container {
                width: 970px
            }
        }

        @media (min-width: 1200px) {
            .mui-container {
                width: 1170px
            }
        }

        .mui-row {
            margin-left: -15px;
            margin-right: -15px
        }

        .mui-col-lg-1, .mui-col-lg-10, .mui-col-lg-11, .mui-col-lg-12, .mui-col-lg-2, .mui-col-lg-3, .mui-col-lg-4, .mui-col-lg-5, .mui-col-lg-6, .mui-col-lg-7, .mui-col-lg-8, .mui-col-lg-9, .mui-col-md-1, .mui-col-md-10, .mui-col-md-11, .mui-col-md-12, .mui-col-md-2, .mui-col-md-3, .mui-col-md-4, .mui-col-md-5, .mui-col-md-6, .mui-col-md-7, .mui-col-md-8, .mui-col-md-9, .mui-col-sm-1, .mui-col-sm-10, .mui-col-sm-11, .mui-col-sm-12, .mui-col-sm-2, .mui-col-sm-3, .mui-col-sm-4, .mui-col-sm-5, .mui-col-sm-6, .mui-col-sm-7, .mui-col-sm-8, .mui-col-sm-9, .mui-col-xs-1, .mui-col-xs-10, .mui-col-xs-11, .mui-col-xs-12, .mui-col-xs-2, .mui-col-xs-3, .mui-col-xs-4, .mui-col-xs-5, .mui-col-xs-6, .mui-col-xs-7, .mui-col-xs-8, .mui-col-xs-9 {
            min-height: 1px;
            padding-left: 15px;
            padding-right: 15px
        }

        .mui-col-xs-1, .mui-col-xs-10, .mui-col-xs-11, .mui-col-xs-12, .mui-col-xs-2, .mui-col-xs-3, .mui-col-xs-4, .mui-col-xs-5, .mui-col-xs-6, .mui-col-xs-7, .mui-col-xs-8, .mui-col-xs-9 {
            float: left
        }

        .mui-col-xs-1 {
            width: 8.33333%
        }

        .mui-col-xs-2 {
            width: 16.66667%
        }

        .mui-col-xs-3 {
            width: 25%
        }

        .mui-col-xs-4 {
            width: 33.33333%
        }

        .mui-col-xs-5 {
            width: 41.66667%
        }

        .mui-col-xs-6 {
            width: 50%
        }

        .mui-col-xs-7 {
            width: 58.33333%
        }

        .mui-col-xs-8 {
            width: 66.66667%
        }

        .mui-col-xs-9 {
            width: 75%
        }

        .mui-col-xs-10 {
            width: 83.33333%
        }

        .mui-col-xs-11 {
            width: 91.66667%
        }

        .mui-col-xs-12 {
            width: 100%
        }

        .mui-col-xs-pull-0 {
            right: auto
        }

        .mui-col-xs-pull-1 {
            right: 8.33333%
        }

        .mui-col-xs-pull-2 {
            right: 16.66667%
        }

        .mui-col-xs-pull-3 {
            right: 25%
        }

        .mui-col-xs-pull-4 {
            right: 33.33333%
        }

        .mui-col-xs-pull-5 {
            right: 41.66667%
        }

        .mui-col-xs-pull-6 {
            right: 50%
        }

        .mui-col-xs-pull-7 {
            right: 58.33333%
        }

        .mui-col-xs-pull-8 {
            right: 66.66667%
        }

        .mui-col-xs-pull-9 {
            right: 75%
        }

        .mui-col-xs-pull-10 {
            right: 83.33333%
        }

        .mui-col-xs-pull-11 {
            right: 91.66667%
        }

        .mui-col-xs-pull-12 {
            right: 100%
        }

        .mui-col-xs-push-0 {
            left: auto
        }

        .mui-col-xs-push-1 {
            left: 8.33333%
        }

        .mui-col-xs-push-2 {
            left: 16.66667%
        }

        .mui-col-xs-push-3 {
            left: 25%
        }

        .mui-col-xs-push-4 {
            left: 33.33333%
        }

        .mui-col-xs-push-5 {
            left: 41.66667%
        }

        .mui-col-xs-push-6 {
            left: 50%
        }

        .mui-col-xs-push-7 {
            left: 58.33333%
        }

        .mui-col-xs-push-8 {
            left: 66.66667%
        }

        .mui-col-xs-push-9 {
            left: 75%
        }

        .mui-col-xs-push-10 {
            left: 83.33333%
        }

        .mui-col-xs-push-11 {
            left: 91.66667%
        }

        .mui-col-xs-push-12 {
            left: 100%
        }

        .mui-col-xs-offset-0 {
            margin-left: 0
        }

        .mui-col-xs-offset-1 {
            margin-left: 8.33333%
        }

        .mui-col-xs-offset-2 {
            margin-left: 16.66667%
        }

        .mui-col-xs-offset-3 {
            margin-left: 25%
        }

        .mui-col-xs-offset-4 {
            margin-left: 33.33333%
        }

        .mui-col-xs-offset-5 {
            margin-left: 41.66667%
        }

        .mui-col-xs-offset-6 {
            margin-left: 50%
        }

        .mui-col-xs-offset-7 {
            margin-left: 58.33333%
        }

        .mui-col-xs-offset-8 {
            margin-left: 66.66667%
        }

        .mui-col-xs-offset-9 {
            margin-left: 75%
        }

        .mui-col-xs-offset-10 {
            margin-left: 83.33333%
        }

        .mui-col-xs-offset-11 {
            margin-left: 91.66667%
        }

        .mui-col-xs-offset-12 {
            margin-left: 100%
        }

        @media (min-width: 768px) {
            .mui-col-sm-1, .mui-col-sm-10, .mui-col-sm-11, .mui-col-sm-12, .mui-col-sm-2, .mui-col-sm-3, .mui-col-sm-4, .mui-col-sm-5, .mui-col-sm-6, .mui-col-sm-7, .mui-col-sm-8, .mui-col-sm-9 {
                float: left
            }

            .mui-col-sm-1 {
                width: 8.33333%
            }

            .mui-col-sm-2 {
                width: 16.66667%
            }

            .mui-col-sm-3 {
                width: 25%
            }

            .mui-col-sm-4 {
                width: 33.33333%
            }

            .mui-col-sm-5 {
                width: 41.66667%
            }

            .mui-col-sm-6 {
                width: 50%
            }

            .mui-col-sm-7 {
                width: 58.33333%
            }

            .mui-col-sm-8 {
                width: 66.66667%
            }

            .mui-col-sm-9 {
                width: 75%
            }

            .mui-col-sm-10 {
                width: 83.33333%
            }

            .mui-col-sm-11 {
                width: 91.66667%
            }

            .mui-col-sm-12 {
                width: 100%
            }

            .mui-col-sm-pull-0 {
                right: auto
            }

            .mui-col-sm-pull-1 {
                right: 8.33333%
            }

            .mui-col-sm-pull-2 {
                right: 16.66667%
            }

            .mui-col-sm-pull-3 {
                right: 25%
            }

            .mui-col-sm-pull-4 {
                right: 33.33333%
            }

            .mui-col-sm-pull-5 {
                right: 41.66667%
            }

            .mui-col-sm-pull-6 {
                right: 50%
            }

            .mui-col-sm-pull-7 {
                right: 58.33333%
            }

            .mui-col-sm-pull-8 {
                right: 66.66667%
            }

            .mui-col-sm-pull-9 {
                right: 75%
            }

            .mui-col-sm-pull-10 {
                right: 83.33333%
            }

            .mui-col-sm-pull-11 {
                right: 91.66667%
            }

            .mui-col-sm-pull-12 {
                right: 100%
            }

            .mui-col-sm-push-0 {
                left: auto
            }

            .mui-col-sm-push-1 {
                left: 8.33333%
            }

            .mui-col-sm-push-2 {
                left: 16.66667%
            }

            .mui-col-sm-push-3 {
                left: 25%
            }

            .mui-col-sm-push-4 {
                left: 33.33333%
            }

            .mui-col-sm-push-5 {
                left: 41.66667%
            }

            .mui-col-sm-push-6 {
                left: 50%
            }

            .mui-col-sm-push-7 {
                left: 58.33333%
            }

            .mui-col-sm-push-8 {
                left: 66.66667%
            }

            .mui-col-sm-push-9 {
                left: 75%
            }

            .mui-col-sm-push-10 {
                left: 83.33333%
            }

            .mui-col-sm-push-11 {
                left: 91.66667%
            }

            .mui-col-sm-push-12 {
                left: 100%
            }

            .mui-col-sm-offset-0 {
                margin-left: 0
            }

            .mui-col-sm-offset-1 {
                margin-left: 8.33333%
            }

            .mui-col-sm-offset-2 {
                margin-left: 16.66667%
            }

            .mui-col-sm-offset-3 {
                margin-left: 25%
            }

            .mui-col-sm-offset-4 {
                margin-left: 33.33333%
            }

            .mui-col-sm-offset-5 {
                margin-left: 41.66667%
            }

            .mui-col-sm-offset-6 {
                margin-left: 50%
            }

            .mui-col-sm-offset-7 {
                margin-left: 58.33333%
            }

            .mui-col-sm-offset-8 {
                margin-left: 66.66667%
            }

            .mui-col-sm-offset-9 {
                margin-left: 75%
            }

            .mui-col-sm-offset-10 {
                margin-left: 83.33333%
            }

            .mui-col-sm-offset-11 {
                margin-left: 91.66667%
            }

            .mui-col-sm-offset-12 {
                margin-left: 100%
            }
        }

        @media (min-width: 992px) {
            .mui-col-md-1, .mui-col-md-10, .mui-col-md-11, .mui-col-md-12, .mui-col-md-2, .mui-col-md-3, .mui-col-md-4, .mui-col-md-5, .mui-col-md-6, .mui-col-md-7, .mui-col-md-8, .mui-col-md-9 {
                float: left
            }

            .mui-col-md-1 {
                width: 8.33333%
            }

            .mui-col-md-2 {
                width: 16.66667%
            }

            .mui-col-md-3 {
                width: 25%
            }

            .mui-col-md-4 {
                width: 33.33333%
            }

            .mui-col-md-5 {
                width: 41.66667%
            }

            .mui-col-md-6 {
                width: 50%
            }

            .mui-col-md-7 {
                width: 58.33333%
            }

            .mui-col-md-8 {
                width: 66.66667%
            }

            .mui-col-md-9 {
                width: 75%
            }

            .mui-col-md-10 {
                width: 83.33333%
            }

            .mui-col-md-11 {
                width: 91.66667%
            }

            .mui-col-md-12 {
                width: 100%
            }

            .mui-col-md-pull-0 {
                right: auto
            }

            .mui-col-md-pull-1 {
                right: 8.33333%
            }

            .mui-col-md-pull-2 {
                right: 16.66667%
            }

            .mui-col-md-pull-3 {
                right: 25%
            }

            .mui-col-md-pull-4 {
                right: 33.33333%
            }

            .mui-col-md-pull-5 {
                right: 41.66667%
            }

            .mui-col-md-pull-6 {
                right: 50%
            }

            .mui-col-md-pull-7 {
                right: 58.33333%
            }

            .mui-col-md-pull-8 {
                right: 66.66667%
            }

            .mui-col-md-pull-9 {
                right: 75%
            }

            .mui-col-md-pull-10 {
                right: 83.33333%
            }

            .mui-col-md-pull-11 {
                right: 91.66667%
            }

            .mui-col-md-pull-12 {
                right: 100%
            }

            .mui-col-md-push-0 {
                left: auto
            }

            .mui-col-md-push-1 {
                left: 8.33333%
            }

            .mui-col-md-push-2 {
                left: 16.66667%
            }

            .mui-col-md-push-3 {
                left: 25%
            }

            .mui-col-md-push-4 {
                left: 33.33333%
            }

            .mui-col-md-push-5 {
                left: 41.66667%
            }

            .mui-col-md-push-6 {
                left: 50%
            }

            .mui-col-md-push-7 {
                left: 58.33333%
            }

            .mui-col-md-push-8 {
                left: 66.66667%
            }

            .mui-col-md-push-9 {
                left: 75%
            }

            .mui-col-md-push-10 {
                left: 83.33333%
            }

            .mui-col-md-push-11 {
                left: 91.66667%
            }

            .mui-col-md-push-12 {
                left: 100%
            }

            .mui-col-md-offset-0 {
                margin-left: 0
            }

            .mui-col-md-offset-1 {
                margin-left: 8.33333%
            }

            .mui-col-md-offset-2 {
                margin-left: 16.66667%
            }

            .mui-col-md-offset-3 {
                margin-left: 25%
            }

            .mui-col-md-offset-4 {
                margin-left: 33.33333%
            }

            .mui-col-md-offset-5 {
                margin-left: 41.66667%
            }

            .mui-col-md-offset-6 {
                margin-left: 50%
            }

            .mui-col-md-offset-7 {
                margin-left: 58.33333%
            }

            .mui-col-md-offset-8 {
                margin-left: 66.66667%
            }

            .mui-col-md-offset-9 {
                margin-left: 75%
            }

            .mui-col-md-offset-10 {
                margin-left: 83.33333%
            }

            .mui-col-md-offset-11 {
                margin-left: 91.66667%
            }

            .mui-col-md-offset-12 {
                margin-left: 100%
            }
        }

        @media (min-width: 1200px) {
            .mui-col-lg-1, .mui-col-lg-10, .mui-col-lg-11, .mui-col-lg-12, .mui-col-lg-2, .mui-col-lg-3, .mui-col-lg-4, .mui-col-lg-5, .mui-col-lg-6, .mui-col-lg-7, .mui-col-lg-8, .mui-col-lg-9 {
                float: left
            }

            .mui-col-lg-1 {
                width: 8.33333%
            }

            .mui-col-lg-2 {
                width: 16.66667%
            }

            .mui-col-lg-3 {
                width: 25%
            }

            .mui-col-lg-4 {
                width: 33.33333%
            }

            .mui-col-lg-5 {
                width: 41.66667%
            }

            .mui-col-lg-6 {
                width: 50%
            }

            .mui-col-lg-7 {
                width: 58.33333%
            }

            .mui-col-lg-8 {
                width: 66.66667%
            }

            .mui-col-lg-9 {
                width: 75%
            }

            .mui-col-lg-10 {
                width: 83.33333%
            }

            .mui-col-lg-11 {
                width: 91.66667%
            }

            .mui-col-lg-12 {
                width: 100%
            }

            .mui-col-lg-pull-0 {
                right: auto
            }

            .mui-col-lg-pull-1 {
                right: 8.33333%
            }

            .mui-col-lg-pull-2 {
                right: 16.66667%
            }

            .mui-col-lg-pull-3 {
                right: 25%
            }

            .mui-col-lg-pull-4 {
                right: 33.33333%
            }

            .mui-col-lg-pull-5 {
                right: 41.66667%
            }

            .mui-col-lg-pull-6 {
                right: 50%
            }

            .mui-col-lg-pull-7 {
                right: 58.33333%
            }

            .mui-col-lg-pull-8 {
                right: 66.66667%
            }

            .mui-col-lg-pull-9 {
                right: 75%
            }

            .mui-col-lg-pull-10 {
                right: 83.33333%
            }

            .mui-col-lg-pull-11 {
                right: 91.66667%
            }

            .mui-col-lg-pull-12 {
                right: 100%
            }

            .mui-col-lg-push-0 {
                left: auto
            }

            .mui-col-lg-push-1 {
                left: 8.33333%
            }

            .mui-col-lg-push-2 {
                left: 16.66667%
            }

            .mui-col-lg-push-3 {
                left: 25%
            }

            .mui-col-lg-push-4 {
                left: 33.33333%
            }

            .mui-col-lg-push-5 {
                left: 41.66667%
            }

            .mui-col-lg-push-6 {
                left: 50%
            }

            .mui-col-lg-push-7 {
                left: 58.33333%
            }

            .mui-col-lg-push-8 {
                left: 66.66667%
            }

            .mui-col-lg-push-9 {
                left: 75%
            }

            .mui-col-lg-push-10 {
                left: 83.33333%
            }

            .mui-col-lg-push-11 {
                left: 91.66667%
            }

            .mui-col-lg-push-12 {
                left: 100%
            }

            .mui-col-lg-offset-0 {
                margin-left: 0
            }

            .mui-col-lg-offset-1 {
                margin-left: 8.33333%
            }

            .mui-col-lg-offset-2 {
                margin-left: 16.66667%
            }

            .mui-col-lg-offset-3 {
                margin-left: 25%
            }

            .mui-col-lg-offset-4 {
                margin-left: 33.33333%
            }

            .mui-col-lg-offset-5 {
                margin-left: 41.66667%
            }

            .mui-col-lg-offset-6 {
                margin-left: 50%
            }

            .mui-col-lg-offset-7 {
                margin-left: 58.33333%
            }

            .mui-col-lg-offset-8 {
                margin-left: 66.66667%
            }

            .mui-col-lg-offset-9 {
                margin-left: 75%
            }

            .mui-col-lg-offset-10 {
                margin-left: 83.33333%
            }

            .mui-col-lg-offset-11 {
                margin-left: 91.66667%
            }

            .mui-col-lg-offset-12 {
                margin-left: 100%
            }
        }

        .mui--text-display4 {
            font-weight: 300;
            font-size: 112px;
            line-height: 112px
        }

        .mui--text-display3 {
            font-weight: 400;
            font-size: 56px;
            line-height: 56px
        }

        .mui--text-display2 {
            font-weight: 400;
            font-size: 45px;
            line-height: 48px
        }

        .mui--text-display1, h1 {
            font-weight: 400;
            font-size: 34px;
            line-height: 40px
        }

        .mui--text-headline, h2 {
            font-weight: 400;
            font-size: 24px;
            line-height: 32px
        }

        .mui--text-title, h3 {
            font-weight: 400;
            font-size: 20px;
            line-height: 28px
        }

        .mui--text-subhead, h4 {
            font-weight: 400;
            font-size: 16px;
            line-height: 24px
        }

        .mui--text-body2, h5 {
            font-weight: 500;
            font-size: 14px;
            line-height: 24px
        }

        .mui--text-body1 {
            font-weight: 400;
            font-size: 14px;
            line-height: 20px
        }

        .mui--text-caption {
            font-weight: 400;
            font-size: 12px;
            line-height: 16px
        }

        .mui--text-menu {
            font-weight: 500;
            font-size: 13px;
            line-height: 17px
        }

        .mui--text-button, .mui-btn {
            font-weight: 500;
            font-size: 14px;
            text-transform: uppercase
        }

        .mui--text-button {
            line-height: 18px
        }

        .mui-panel {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0;
            box-shadow: 0 2px 2px 0 rgba(0, 0, 0, .16), 0 0 2px 0 rgba(0, 0, 0, .12)
        }

        .mui-btn {
            animation-duration: .1ms;
            animation-name: mui-node-inserted;
            color: rgba(0, 0, 0, .87);
            -webkit-transition: all .2s ease-in-out;
            transition: all .2s ease-in-out;
            display: inline-block;
            height: 36px;
            padding: 0 26px;
            margin-top: 6px;
            margin-bottom: 6px;
            border: none;
            border-radius: 2px;
            cursor: pointer;
            -ms-touch-action: manipulation;
            touch-action: manipulation;
            text-align: center;
            line-height: 36px;
            white-space: nowrap;
            user-select: none;
            letter-spacing: .03em;
            overflow: hidden
        }

        h1, h2, h3, h4, h5, h6, legend {
            margin-bottom: 10px
        }

        .mui-btn:active, .mui-btn:focus, .mui-btn:hover {
            background-color: #fff;
            outline: 0;
            text-decoration: none;
            color: rgba(0, 0, 0, .87)
        }

        .mui-btn[disabled]:active, .mui-btn[disabled]:focus, .mui-btn[disabled]:hover {
            color: rgba(0, 0, 0, .87);
            background-color: #FFF
        }

        .mui-btn.mui-btn--flat {
            color: rgba(0, 0, 0, .87);
            background-color: transparent
        }

        .mui-btn.mui-btn--flat:active, .mui-btn.mui-btn--flat:focus, .mui-btn.mui-btn--flat:hover {
            color: rgba(0, 0, 0, .87);
            background-color: #f2f2f2
        }

        .mui-btn.mui-btn--flat[disabled]:active, .mui-btn.mui-btn--flat[disabled]:focus, .mui-btn.mui-btn--flat[disabled]:hover {
            color: rgba(0, 0, 0, .87);
            background-color: transparent
        }

        .mui-btn:focus, .mui-btn:hover {
            box-shadow: 0 0 2px rgba(0, 0, 0, .12), 0 2px 2px rgba(0, 0, 0, .2)
        }

        .mui-btn:active {
            box-shadow: 0 10px 20px rgba(0, 0, 0, .19), 0 6px 6px rgba(0, 0, 0, .23)
        }

        .mui-btn.mui--is-disabled, .mui-btn:disabled {
            cursor: not-allowed;
            pointer-events: none;
            opacity: .6;
            box-shadow: none
        }

        .mui-btn + .mui-btn {
            margin-left: 8px
        }

        .mui-btn--flat {
            background-color: transparent
        }

        .mui-btn--flat:active, .mui-btn--flat:focus, .mui-btn--flat:hover {
            box-shadow: none;
            background-color: #f2f2f2
        }

        .mui-btn--fab, .mui-btn--raised {
            box-shadow: 0 0 2px rgba(0, 0, 0, .12), 0 2px 2px rgba(0, 0, 0, .2)
        }

        .mui-btn--fab:active, .mui-btn--raised:active {
            box-shadow: 0 10px 20px rgba(0, 0, 0, .19), 0 6px 6px rgba(0, 0, 0, .23)
        }

        .mui-btn--fab {
            padding: 0;
            width: 55px;
            height: 55px;
            line-height: 55px;
            border-radius: 50%;
            z-index: 1
        }

        .mui-btn--primary {
            color: #FFF;
            background-color: #2196F3
        }

        .mui-btn--primary:active, .mui-btn--primary:focus, .mui-btn--primary:hover {
            color: #FFF;
            background-color: #39a1f4
        }

        .mui-btn--primary[disabled]:active, .mui-btn--primary[disabled]:focus, .mui-btn--primary[disabled]:hover {
            color: #FFF;
            background-color: #2196F3
        }

        .mui-btn--primary.mui-btn--flat {
            color: #2196F3;
            background-color: transparent
        }

        .mui-btn--primary.mui-btn--flat:active, .mui-btn--primary.mui-btn--flat:focus, .mui-btn--primary.mui-btn--flat:hover {
            color: #2196F3;
            background-color: #f2f2f2
        }

        .mui-btn--primary.mui-btn--flat[disabled]:active, .mui-btn--primary.mui-btn--flat[disabled]:focus, .mui-btn--primary.mui-btn--flat[disabled]:hover {
            color: #2196F3;
            background-color: transparent
        }

        .mui-btn--dark {
            color: #FFF;
            background-color: #424242
        }

        .mui-btn--dark:active, .mui-btn--dark:focus, .mui-btn--dark:hover {
            color: #FFF;
            background-color: #4f4f4f
        }

        .mui-btn--dark[disabled]:active, .mui-btn--dark[disabled]:focus, .mui-btn--dark[disabled]:hover {
            color: #FFF;
            background-color: #424242
        }

        .mui-btn--dark.mui-btn--flat {
            color: #424242;
            background-color: transparent
        }

        .mui-btn--dark.mui-btn--flat:active, .mui-btn--dark.mui-btn--flat:focus, .mui-btn--dark.mui-btn--flat:hover {
            color: #424242;
            background-color: #f2f2f2
        }

        .mui-btn--dark.mui-btn--flat[disabled]:active, .mui-btn--dark.mui-btn--flat[disabled]:focus, .mui-btn--dark.mui-btn--flat[disabled]:hover {
            color: #424242;
            background-color: transparent
        }

        .mui-btn--danger {
            color: #FFF;
            background-color: #F44336
        }

        .mui-btn--danger:active, .mui-btn--danger:focus, .mui-btn--danger:hover {
            color: #FFF;
            background-color: #f55a4e
        }

        .mui-btn--danger[disabled]:active, .mui-btn--danger[disabled]:focus, .mui-btn--danger[disabled]:hover {
            color: #FFF;
            background-color: #F44336
        }

        .mui-btn--danger.mui-btn--flat {
            color: #F44336;
            background-color: transparent
        }

        .mui-btn--danger.mui-btn--flat:active, .mui-btn--danger.mui-btn--flat:focus, .mui-btn--danger.mui-btn--flat:hover {
            color: #F44336;
            background-color: #f2f2f2
        }

        .mui-btn--danger.mui-btn--flat[disabled]:active, .mui-btn--danger.mui-btn--flat[disabled]:focus, .mui-btn--danger.mui-btn--flat[disabled]:hover {
            color: #F44336;
            background-color: transparent
        }

        .mui-btn--accent {
            color: #FFF;
            background-color: #FF4081
        }

        .mui-btn--accent:active, .mui-btn--accent:focus, .mui-btn--accent:hover {
            color: #FFF;
            background-color: #ff5a92
        }

        .mui-btn--accent[disabled]:active, .mui-btn--accent[disabled]:focus, .mui-btn--accent[disabled]:hover {
            color: #FFF;
            background-color: #FF4081
        }

        .mui-btn--accent.mui-btn--flat {
            color: #FF4081;
            background-color: transparent
        }

        .mui-btn--accent.mui-btn--flat:active, .mui-btn--accent.mui-btn--flat:focus, .mui-btn--accent.mui-btn--flat:hover {
            color: #FF4081;
            background-color: #f2f2f2
        }

        .mui-btn--accent.mui-btn--flat[disabled]:active, .mui-btn--accent.mui-btn--flat[disabled]:focus, .mui-btn--accent.mui-btn--flat[disabled]:hover {
            color: #FF4081;
            background-color: transparent
        }

        .mui-btn--small {
            height: 30.6px;
            line-height: 30.6px;
            padding: 0 16px;
            font-size: 13px
        }

        .mui-btn--large {
            height: 54px;
            line-height: 54px;
            padding: 0 26px;
            font-size: 14px
        }

        .mui-btn--fab.mui-btn--small {
            width: 44px;
            height: 44px;
            line-height: 44px
        }

        .mui-btn--fab.mui-btn--large {
            width: 75px;
            height: 75px;
            line-height: 75px
        }

        .mui-ripple-effect {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            opacity: 0;
            -webkit-animation: mui-ripple-animation 2s;
            animation: mui-ripple-animation 2s
        }

        @-webkit-keyframes mui-ripple-animation {
            from {
                -webkit-transform: scale(1);
                transform: scale(1);
                opacity: .4
            }
            to {
                -webkit-transform: scale(100);
                transform: scale(100);
                opacity: 0
            }
        }

        @keyframes mui-ripple-animation {
            from {
                -webkit-transform: scale(1);
                transform: scale(1);
                opacity: .4
            }
            to {
                -webkit-transform: scale(100);
                transform: scale(100);
                opacity: 0
            }
        }

        .mui-btn > .mui-ripple-effect {
            background-color: #a6a6a6
        }

        .mui-btn--accent > .mui-ripple-effect, .mui-btn--danger > .mui-ripple-effect, .mui-btn--dark > .mui-ripple-effect, .mui-btn--primary > .mui-ripple-effect {
            background-color: #FFF
        }

        .mui-btn--flat > .mui-ripple-effect {
            background-color: #a6a6a6
        }

        .mui--appbar-height {
            height: 56px
        }

        .mui--appbar-min-height, .mui-appbar {
            min-height: 56px
        }

        .mui--appbar-line-height {
            line-height: 56px
        }

        .mui--appbar-top {
            top: 56px
        }

        @media (orientation: landscape) and (max-height: 480px) {
            .mui--appbar-height {
                height: 48px
            }

            .mui--appbar-min-height, .mui-appbar {
                min-height: 48px
            }

            .mui--appbar-line-height {
                line-height: 48px
            }

            .mui--appbar-top {
                top: 48px
            }
        }

        @media (min-width: 480px) {
            .mui--appbar-height {
                height: 64px
            }

            .mui--appbar-min-height, .mui-appbar {
                min-height: 64px
            }

            .mui--appbar-line-height {
                line-height: 64px
            }

            .mui--appbar-top {
                top: 64px
            }
        }

        .mui-appbar {
            background-color: #2196F3;
            color: #FFF
        }

        strong {
            font-weight: 700
        }

        abbr[title] {
            cursor: help;
            border-bottom: 1px dotted #2196F3
        }

        h1, h2, h3 {
            margin-top: 20px
        }

        h4, h5, h6 {
            margin-top: 10px
        }

        .mui-divider {
            display: block;
            height: 1px;
            background-color: rgba(0, 0, 0, .12)
        }

        .mui--divider-top {
            border-top: 1px solid rgba(0, 0, 0, .12)
        }

        .mui--divider-bottom {
            border-bottom: 1px solid rgba(0, 0, 0, .12)
        }

        .mui--divider-left {
            border-left: 1px solid rgba(0, 0, 0, .12)
        }

        .mui--divider-right {
            border-right: 1px solid rgba(0, 0, 0, .12)
        }

        legend {
            display: block;
            width: 100%;
            padding: 0;
            font-size: 21px;
            color: rgba(0, 0, 0, .87);
            line-height: inherit;
            border: 0
        }

        input[type=search] {
            box-sizing: border-box;
            -webkit-appearance: none
        }

        input[type=file]:focus, input[type=checkbox]:focus, input[type=radio]:focus {
            outline: dotted thin;
            outline: -webkit-focus-ring-color auto 5px;
            outline-offset: -2px
        }

        input[type=checkbox]:disabled, input[type=radio]:disabled {
            cursor: not-allowed
        }

        .mui-textfield {
            display: block;
            padding-top: 15px;
            margin-bottom: 20px;
            position: relative
        }

        .mui-textfield > label {
            position: absolute;
            top: 0;
            display: block;
            width: 100%;
            color: rgba(0, 0, 0, .54);
            font-size: 12px;
            font-weight: 400;
            line-height: 15px;
            overflow-x: hidden;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        .mui-textfield > textarea {
            padding-top: 5px;
            min-height: 64px
        }

        .mui-textfield > input:focus ~ label, .mui-textfield > textarea:focus ~ label {
            color: #2196F3
        }

        .mui-textfield--float-label > label {
            position: absolute;
            top: 15px;
            font-size: 16px;
            line-height: 32px;
            color: rgba(0, 0, 0, .26);
            text-overflow: clip;
            cursor: text;
            pointer-events: none
        }

        .mui-textfield--float-label > input:focus ~ label, .mui-textfield--float-label > textarea:focus ~ label {
            top: 0;
            font-size: 12px;
            line-height: 15px;
            text-overflow: ellipsis
        }

        .mui-textfield--float-label > input:not(:focus).mui--is-not-empty ~ label, .mui-textfield--float-label > input:not(:focus):not(:empty):not(.mui--is-empty):not(.mui--is-not-empty) ~ label, .mui-textfield--float-label > input:not(:focus)[value]:not([value=""]):not(.mui--is-empty):not(.mui--is-not-empty) ~ label, .mui-textfield--float-label > textarea:not(:focus).mui--is-not-empty ~ label, .mui-textfield--float-label > textarea:not(:focus):not(:empty):not(.mui--is-empty):not(.mui--is-not-empty) ~ label, .mui-textfield--float-label > textarea:not(:focus)[value]:not([value=""]):not(.mui--is-empty):not(.mui--is-not-empty) ~ label {
            color: rgba(0, 0, 0, .54);
            font-size: 12px;
            line-height: 15px;
            top: 0;
            text-overflow: ellipsis
        }

        .mui-textfield--wrap-label {
            display: table;
            width: 100%;
            padding-top: 0
        }

        .mui-textfield--wrap-label:not(.mui-textfield--float-label) > label {
            display: table-header-group;
            position: static;
            white-space: normal;
            overflow-x: visible
        }

        .mui-textfield > input, .mui-textfield > textarea {
            animation-duration: .1ms;
            animation-name: mui-node-inserted;
            display: block;
            background-color: transparent;
            color: rgba(0, 0, 0, .87);
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, .26);
            outline: 0;
            height: 32px;
            width: 100%;
            font-size: 16px;
            padding: 0;
            box-shadow: none;
            border-radius: 0
        }

        .mui-select > select, [data-mui-toggle=dropdown] {
            -webkit-animation-duration: .1ms;
            -webkit-animation-name: mui-node-inserted;
            outline: 0
        }

        .mui-select:focus > select, .mui-textfield > input:focus, .mui-textfield > textarea:focus {
            border-color: #2196F3;
            border-width: 2px
        }

        .mui-textfield > input:-moz-read-only, .mui-textfield > input:disabled, .mui-textfield > textarea:-moz-read-only, .mui-textfield > textarea:disabled {
            cursor: not-allowed;
            background-color: transparent;
            opacity: 1
        }

        .mui-textfield > input:disabled, .mui-textfield > input:read-only, .mui-textfield > textarea:disabled, .mui-textfield > textarea:read-only {
            cursor: not-allowed;
            background-color: transparent;
            opacity: 1
        }

        .mui-textfield > input::-webkit-input-placeholder, .mui-textfield > textarea::-webkit-input-placeholder {
            color: rgba(0, 0, 0, .26);
            opacity: 1
        }

        .mui-textfield > input::-moz-placeholder, .mui-textfield > textarea::-moz-placeholder {
            color: rgba(0, 0, 0, .26);
            opacity: 1
        }

        .mui-textfield > input:-ms-input-placeholder, .mui-textfield > textarea:-ms-input-placeholder {
            color: rgba(0, 0, 0, .26);
            opacity: 1
        }

        .mui-textfield > input::placeholder, .mui-textfield > textarea::placeholder {
            color: rgba(0, 0, 0, .26);
            opacity: 1
        }

        .mui-textfield > textarea {
            height: auto
        }

        .mui-textfield > input:focus {
            height: 33px;
            margin-bottom: -1px
        }

        .mui-checkbox, .mui-radio {
            position: relative;
            display: block;
            margin-top: 10px;
            margin-bottom: 10px
        }

        .mui-checkbox > label, .mui-radio > label {
            min-height: 20px;
            padding-left: 20px;
            margin-bottom: 0;
            font-weight: 400;
            cursor: pointer
        }

        .mui-checkbox--inline > label > input[type=checkbox], .mui-checkbox > label > input[type=checkbox], .mui-radio--inline > label > input[type=radio], .mui-radio > label > input[type=radio] {
            position: absolute;
            margin-left: -20px;
            margin-top: 4px
        }

        .mui-checkbox + .mui-checkbox, .mui-radio + .mui-radio {
            margin-top: -5px
        }

        .mui-checkbox--inline, .mui-radio--inline {
            display: inline-block;
            padding-left: 20px;
            margin-bottom: 0;
            vertical-align: middle;
            font-weight: 400;
            cursor: pointer
        }

        .mui-checkbox--inline > input[type=checkbox], .mui-checkbox--inline > input[type=radio], .mui-checkbox--inline > label > input[type=checkbox], .mui-checkbox--inline > label > input[type=radio], .mui-radio--inline > input[type=checkbox], .mui-radio--inline > input[type=radio], .mui-radio--inline > label > input[type=checkbox], .mui-radio--inline > label > input[type=radio] {
            margin: 4px 0 0;
            line-height: normal
        }

        .mui-checkbox--inline + .mui-checkbox--inline, .mui-radio--inline + .mui-radio--inline {
            margin-top: 0;
            margin-left: 10px
        }

        .mui-select {
            display: block;
            padding-top: 15px;
            margin-bottom: 20px;
            position: relative
        }

        .mui-select:focus {
            outline: 0
        }

        .mui-select:focus > select {
            height: 33px;
            margin-bottom: -1px
        }

        .mui-select > select {
            animation-duration: .1ms;
            animation-name: mui-node-inserted;
            display: block;
            height: 32px;
            width: 100%;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, .26);
            border-radius: 0;
            box-shadow: none;
            background-color: transparent;
            background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGhlaWdodD0iNiIgd2lkdGg9IjEwIj48cG9seWdvbiBwb2ludHM9IjAsMCAxMCwwIDUsNiIgc3R5bGU9ImZpbGw6cmdiYSgwLDAsMCwuMjQpOyIvPjwvc3ZnPg==);
            background-repeat: no-repeat;
            background-position: right center;
            cursor: pointer;
            color: rgba(0, 0, 0, .87);
            font-size: 16px;
            padding: 0 25px 0 0
        }

        .mui-select > select::-ms-expand {
            display: none
        }

        .mui-select > select:focus {
            outline: 0;
            height: 33px;
            margin-bottom: -1px;
            border-color: #2196F3;
            border-width: 2px
        }

        .mui-select > select:disabled {
            color: rgba(0, 0, 0, .26);
            cursor: not-allowed;
            background-color: transparent;
            opacity: 1
        }

        .mui-select__menu {
            position: absolute;
            z-index: 1;
            min-width: 100%;
            overflow-y: auto;
            padding: 8px 0;
            background-color: #FFF;
            font-size: 16px
        }

        .mui-select__menu > div {
            padding: 0 22px;
            height: 42px;
            line-height: 42px;
            cursor: pointer;
            white-space: nowrap
        }

        .mui-select__menu > div:hover {
            background-color: #E0E0E0
        }

        .mui-select__menu > div[selected] {
            background-color: #EEE
        }

        @media (min-width: 768px) {
            .mui-form--inline > .mui-textfield {
                display: inline-block;
                margin-bottom: 0
            }

            .mui-form--inline > .mui-checkbox, .mui-form--inline > .mui-radio {
                display: inline-block;
                margin-top: 0;
                margin-bottom: 0;
                vertical-align: middle
            }

            .mui-form--inline > .mui-checkbox > label, .mui-form--inline > .mui-radio > label {
                padding-left: 0
            }

            .mui-form--inline > .mui-checkbox > label > input[type=checkbox], .mui-form--inline > .mui-radio > label > input[type=radio] {
                position: relative;
                margin-left: 0
            }

            .mui-form--inline > .mui-select {
                display: inline-block
            }

            .mui-form--inline > .mui-btn {
                margin-bottom: 0;
                margin-top: 0;
                vertical-align: bottom
            }
        }

        .mui-textfield > input:invalid:not(:focus):not(:required), .mui-textfield > input:invalid:not(:focus):required.mui--is-empty.mui--is-dirty, .mui-textfield > input:invalid:not(:focus):required.mui--is-not-empty, .mui-textfield > input:invalid:not(:focus):required:not(:empty):not(.mui--is-empty):not(.mui--is-not-empty), .mui-textfield > input:invalid:not(:focus):required[value]:not([value=""]):not(.mui--is-empty):not(.mui--is-not-empty), .mui-textfield > textarea:invalid:not(:focus):not(:required), .mui-textfield > textarea:invalid:not(:focus):required.mui--is-empty.mui--is-dirty, .mui-textfield > textarea:invalid:not(:focus):required.mui--is-not-empty, .mui-textfield > textarea:invalid:not(:focus):required:not(:empty):not(.mui--is-empty):not(.mui--is-not-empty), .mui-textfield > textarea:invalid:not(:focus):required[value]:not([value=""]):not(.mui--is-empty):not(.mui--is-not-empty) {
            border-color: #F44336;
            border-width: 2px
        }

        .mui-textfield > input:invalid:not(:focus):not(:required), .mui-textfield > input:invalid:not(:focus):required.mui--is-empty.mui--is-dirty, .mui-textfield > input:invalid:not(:focus):required.mui--is-not-empty, .mui-textfield > input:invalid:not(:focus):required:not(:empty):not(.mui--is-empty):not(.mui--is-not-empty), .mui-textfield > input:invalid:not(:focus):required[value]:not([value=""]):not(.mui--is-empty):not(.mui--is-not-empty) {
            height: 33px;
            margin-bottom: -1px
        }

        .mui-textfield:not(.mui-textfield--float-label) > input:invalid:not(:focus):required.mui--is-empty.mui--is-dirty ~ label, .mui-textfield:not(.mui-textfield--float-label) > textarea:invalid:not(:focus):required.mui--is-empty.mui--is-dirty ~ label, .mui-textfield > input:invalid:not(:focus):not(:required) ~ label, .mui-textfield > input:invalid:not(:focus):required.mui--is-not-empty ~ label, .mui-textfield > input:invalid:not(:focus):required:not(:empty):not(.mui--is-empty):not(.mui--is-not-empty) ~ label, .mui-textfield > input:invalid:not(:focus):required[value]:not([value=""]):not(.mui--is-empty):not(.mui--is-not-empty) ~ label, .mui-textfield > textarea:invalid:not(:focus):not(:required) ~ label, .mui-textfield > textarea:invalid:not(:focus):required.mui--is-not-empty ~ label, .mui-textfield > textarea:invalid:not(:focus):required:not(:empty):not(.mui--is-empty):not(.mui--is-not-empty) ~ label, .mui-textfield > textarea:invalid:not(:focus):required[value]:not([value=""]):not(.mui--is-empty):not(.mui--is-not-empty) ~ label {
            color: #F44336
        }

        th {
            text-align: left
        }

        .mui-table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px
        }

        .mui-table > tbody > tr > td, .mui-table > tbody > tr > th, .mui-table > tfoot > tr > td, .mui-table > tfoot > tr > th, .mui-table > thead > tr > td, .mui-table > thead > tr > th {
            padding: 10px;
            line-height: 1.429
        }

        .mui-table > thead > tr > th {
            border-bottom: 2px solid rgba(0, 0, 0, .12);
            font-weight: 700
        }

        .mui-table > tbody + tbody {
            border-top: 2px solid rgba(0, 0, 0, .12)
        }

        .mui-table.mui-table--bordered > tbody > tr > td {
            border-bottom: 1px solid rgba(0, 0, 0, .12)
        }

        .mui-dropdown {
            display: inline-block;
            position: relative
        }

        [data-mui-toggle=dropdown] {
            animation-duration: .1ms;
            animation-name: mui-node-inserted
        }

        .mui-dropdown__menu {
            position: absolute;
            top: 100%;
            left: 0;
            display: none;
            min-width: 160px;
            padding: 5px 0;
            margin: 2px 0 0;
            list-style: none;
            font-size: 14px;
            text-align: left;
            background-color: #FFF;
            border-radius: 2px;
            z-index: 1;
            background-clip: padding-box
        }

        .mui-dropdown__menu.mui--is-open {
            display: block
        }

        .mui-dropdown__menu > li > a {
            display: block;
            padding: 3px 20px;
            clear: both;
            font-weight: 400;
            line-height: 1.429;
            color: rgba(0, 0, 0, .87);
            white-space: nowrap
        }

        .mui-dropdown__menu > li > a:focus, .mui-dropdown__menu > li > a:hover {
            text-decoration: none;
            color: rgba(0, 0, 0, .87);
            background-color: #EEE
        }

        .mui-dropdown__menu > .mui--is-disabled > a, .mui-dropdown__menu > .mui--is-disabled > a:focus, .mui-dropdown__menu > .mui--is-disabled > a:hover {
            color: #EEE
        }

        .mui-dropdown__menu > .mui--is-disabled > a:focus, .mui-dropdown__menu > .mui--is-disabled > a:hover {
            text-decoration: none;
            background-color: transparent;
            background-image: none;
            cursor: not-allowed
        }

        .mui-dropdown__menu--right {
            left: auto;
            right: 0
        }

        .mui-tabs__bar {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
            background-color: transparent;
            white-space: nowrap
        }

        .mui-tabs__bar > li {
            display: inline-block
        }

        .mui-tabs__bar > li > a {
            display: block;
            white-space: nowrap;
            text-transform: uppercase;
            font-weight: 500;
            font-size: 14px;
            color: rgba(0, 0, 0, .87);
            cursor: default;
            height: 48px;
            line-height: 48px;
            padding-left: 24px;
            padding-right: 24px;
            user-select: none
        }

        .mui-tabs__bar > li > a:hover {
            text-decoration: none
        }

        .mui-tabs__bar > li.mui--is-active {
            border-bottom: 2px solid #2196F3
        }

        .mui-tabs__bar > li.mui--is-active > a {
            color: #2196F3
        }

        .mui-tabs__bar.mui-tabs__bar--justified {
            display: table;
            width: 100%;
            table-layout: fixed
        }

        .mui-tabs__bar.mui-tabs__bar--justified > li {
            display: table-cell
        }

        .mui-tabs__bar.mui-tabs__bar--justified > li > a {
            text-align: center;
            padding-left: 0;
            padding-right: 0
        }

        .mui-tabs__pane {
            display: none
        }

        .mui-tabs__pane.mui--is-active {
            display: block
        }

        [data-mui-toggle=tab] {
            -webkit-animation-duration: .1ms;
            animation-duration: .1ms;
            -webkit-animation-name: mui-node-inserted;
            animation-name: mui-node-inserted
        }

        #mui-overlay {
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 99999999;
            background-color: rgba(0, 0, 0, .2);
            overflow: auto
        }

        @-webkit-keyframes mui-node-inserted {
            from {
                opacity: .99
            }
            to {
                opacity: 1
            }
        }

        @keyframes mui-node-inserted {
            from {
                opacity: .99
            }
            to {
                opacity: 1
            }
        }

        .mui--no-transition {
            -webkit-transition: none !important;
            transition: none !important
        }

        .mui--no-user-select {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none
        }

        .mui-caret {
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 2px;
            vertical-align: middle;
            border-top: 4px solid;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent
        }

        .mui--text-left {
            text-align: left !important
        }

        .mui--text-right {
            text-align: right !important
        }

        .mui--text-center {
            text-align: center !important
        }

        .mui--text-justify {
            text-align: justify !important
        }

        .mui--text-nowrap {
            white-space: nowrap !important
        }

        .mui--align-baseline {
            vertical-align: baseline !important
        }

        .mui--align-top {
            vertical-align: top !important
        }

        .mui--align-middle {
            vertical-align: middle !important
        }

        .mui--align-bottom {
            vertical-align: bottom !important
        }

        .mui--text-danger {
            color: #F44336
        }

        .mui--text-black {
            color: #000
        }

        .mui--text-black-87 {
            color: rgba(0, 0, 0, .87)
        }

        .mui--text-black-54 {
            color: rgba(0, 0, 0, .54)
        }

        .mui--text-black-26 {
            color: rgba(0, 0, 0, .26)
        }

        .mui--text-black-12 {
            color: rgba(0, 0, 0, .12)
        }

        .mui--text-white {
            color: #FFF
        }

        .mui--text-white-70 {
            color: rgba(255, 255, 255, .7)
        }

        .mui--text-white-30 {
            color: rgba(255, 255, 255, .3)
        }

        .mui--text-white-12 {
            color: rgba(255, 255, 255, .12)
        }

        .mui--text-accent {
            color: #FF4081
        }

        .mui--text-accent-87 {
            color: rgba(255, 64, 129, .87)
        }

        .mui--text-accent-54 {
            color: rgba(255, 64, 129, .54)
        }

        .mui--text-accent-26 {
            color: rgba(255, 64, 129, .26)
        }

        .mui--text-accent-12 {
            color: rgba(255, 64, 129, .12)
        }

        .mui-list--unstyled {
            padding-left: 0;
            list-style: none
        }

        .mui-list--inline {
            padding-left: 0;
            list-style: none;
            margin-left: -5px
        }

        .mui-list--inline > li {
            display: inline-block;
            padding-left: 5px;
            padding-right: 5px
        }

        .mui--z1, .mui-dropdown__menu, .mui-select__menu {
            box-shadow: 0 1px 3px rgba(0, 0, 0, .12), 0 1px 2px rgba(0, 0, 0, .24)
        }

        .mui--z2 {
            box-shadow: 0 3px 6px rgba(0, 0, 0, .16), 0 3px 6px rgba(0, 0, 0, .23)
        }

        .mui--z3 {
            box-shadow: 0 10px 20px rgba(0, 0, 0, .19), 0 6px 6px rgba(0, 0, 0, .23)
        }

        .mui--z4 {
            box-shadow: 0 14px 28px rgba(0, 0, 0, .25), 0 10px 10px rgba(0, 0, 0, .22)
        }

        .mui--z5 {
            box-shadow: 0 19px 38px rgba(0, 0, 0, .3), 0 15px 12px rgba(0, 0, 0, .22)
        }

        .mui--clearfix:after, .mui--clearfix:before {
            content: " ";
            display: table
        }

        .mui--pull-right {
            float: right !important
        }

        .mui--pull-left {
            float: left !important
        }

        .mui--hide {
            display: none !important
        }

        .mui--show {
            display: block !important
        }

        .mui--invisible {
            visibility: hidden
        }

        .mui--overflow-hidden {
            overflow: hidden !important
        }

        .mui--visible-lg-block, .mui--visible-lg-inline, .mui--visible-lg-inline-block, .mui--visible-md-block, .mui--visible-md-inline, .mui--visible-md-inline-block, .mui--visible-sm-block, .mui--visible-sm-inline, .mui--visible-sm-inline-block, .mui--visible-xs-block, .mui--visible-xs-inline, .mui--visible-xs-inline-block {
            display: none !important
        }

        @media (max-width: 767px) {
            .mui-visible-xs {
                display: block !important
            }

            table.mui-visible-xs {
                display: table
            }

            tr.mui-visible-xs {
                display: table-row !important
            }

            td.mui-visible-xs, th.mui-visible-xs {
                display: table-cell !important
            }

            .mui--visible-xs-block {
                display: block !important
            }

            .mui--visible-xs-inline {
                display: inline !important
            }

            .mui--visible-xs-inline-block {
                display: inline-block !important
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            .mui--visible-sm {
                display: block !important
            }

            table.mui--visible-sm {
                display: table
            }

            tr.mui--visible-sm {
                display: table-row !important
            }

            td.mui--visible-sm, th.mui--visible-sm {
                display: table-cell !important
            }

            .mui--visible-sm-block {
                display: block !important
            }

            .mui--visible-sm-inline {
                display: inline !important
            }

            .mui--visible-sm-inline-block {
                display: inline-block !important
            }
        }

        @media (min-width: 992px) and (max-width: 1199px) {
            .mui--visible-md {
                display: block !important
            }

            table.mui--visible-md {
                display: table
            }

            tr.mui--visible-md {
                display: table-row !important
            }

            td.mui--visible-md, th.mui--visible-md {
                display: table-cell !important
            }

            .mui--visible-md-block {
                display: block !important
            }

            .mui--visible-md-inline {
                display: inline !important
            }

            .mui--visible-md-inline-block {
                display: inline-block !important
            }
        }

        @media (min-width: 1200px) {
            .mui--visible-lg {
                display: block !important
            }

            table.mui--visible-lg {
                display: table
            }

            tr.mui--visible-lg {
                display: table-row !important
            }

            td.mui--visible-lg, th.mui--visible-lg {
                display: table-cell !important
            }

            .mui--visible-lg-block {
                display: block !important
            }

            .mui--visible-lg-inline {
                display: inline !important
            }

            .mui--visible-lg-inline-block {
                display: inline-block !important
            }

            .mui--hidden-lg {
                display: none !important
            }
        }

        @media (max-width: 767px) {
            .mui--hidden-xs {
                display: none !important
            }
        }

        @media (min-width: 768px) and (max-width: 991px) {
            .mui--hidden-sm {
                display: none !important
            }
        }

        @media (min-width: 992px) and (max-width: 1199px) {
            .mui--hidden-md {
                display: none !important
            }
        }</style>
    <style>#header, #sidedrawer {
            position: fixed;
            z-index: 2;
            left: 0;
            top: 0
        }

        #monsters-list > li, .dropdown-button {
            box-shadow: 0 1px 3px rgba(0, 0, 0, .12), 0 1px 2px rgba(0, 0, 0, .24)
        }

        body, html {
            height: 100%;
            background-color: #eee
        }

        body, button, html, input, textarea {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-shadow: 1px 1px 1px rgba(0, 0, 0, .004)
        }

        #header {
            right: 0;
            -webkit-transition: -webkit-transform .2s ease-in-out;
            transition: -webkit-transform .2s ease-in-out;
            transition: transform .2s ease-in-out;
            transition: transform .2s ease-in-out, -webkit-transform .2s ease-in-out
        }

        #content-wrapper, #sidedrawer {
            -webkit-transition: -webkit-transform .2s ease-in-out
        }

        #sidedrawer {
            bottom: 0;
            width: 200px;
            -webkit-transform: translate(-200px, 0);
            transform: translate(-200px, 0);
            overflow: auto;
            background-color: #fff;
            transition: -webkit-transform .2s ease-in-out;
            transition: transform .2s ease-in-out;
            transition: transform .2s ease-in-out, -webkit-transform .2s ease-in-out
        }

        #content-wrapper {
            min-height: 100%;
            overflow-x: hidden;
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
            transition: -webkit-transform .2s ease-in-out;
            transition: transform .2s ease-in-out;
            transition: transform .2s ease-in-out, -webkit-transform .2s ease-in-out;
            margin-bottom: -160px;
            padding-bottom: 160px
        }

        #footer {
            height: 160px;
            font-size: 13px;
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0);
            -webkit-transition: -webkit-transform .2s ease-in-out;
            transition: -webkit-transform .2s ease-in-out;
            transition: transform .2s ease-in-out;
            transition: transform .2s ease-in-out, -webkit-transform .2s ease-in-out;
            background-color: #a040a0;
            color: #fff
        }

        @media (min-width: 768px) {
            #header {
                -webkit-transform: translate(200px, 0);
                transform: translate(200px, 0)
            }

            #sidedrawer, body.hide-sidedrawer #header {
                -webkit-transform: translate(0, 0);
                transform: translate(0, 0)
            }

            #content-wrapper, #footer {
                margin-left: 200px
            }

            body.hide-sidedrawer #sidedrawer {
                -webkit-transform: translate(-200px, 0);
                transform: translate(-200px, 0)
            }

            body.hide-sidedrawer #content-wrapper, body.hide-sidedrawer #footer {
                margin-left: 0
            }
        }

        #sidedrawer.active {
            -webkit-transform: translate(0, 0);
            transform: translate(0, 0)
        }

        .sidedrawer-toggle {
            color: #fff;
            cursor: pointer;
            font-size: 20px;
            line-height: 20px;
            margin-right: 10px;
            width: 32px;
            height: 32px
        }

        .sidedrawer-toggle svg {
            vertical-align: middle;
            margin-left: -1px
        }

        .sidedrawer-toggle:hover {
            color: #fff;
            text-decoration: none
        }

        #sidedrawer-brand {
            padding-left: 20px
        }

        #sidedrawer ul {
            list-style: none
        }

        #sidedrawer > ul {
            padding-left: 0
        }

        #sidedrawer > ul > li:first-child {
            padding-top: 15px
        }

        #sidedrawer strong {
            display: block;
            padding: 15px 22px;
            cursor: pointer
        }

        #sidedrawer strong:hover {
            background-color: #E0E0E0
        }

        #sidedrawer strong + ul > li {
            padding: 6px 0
        }

        #footer a {
            color: #fff;
            text-decoration: underline
        }

        #sidedrawer a, #sidedrawer a:hover {
            color: rgba(0, 0, 0, .87);
            text-decoration: none
        }

        @media (max-width: 767px) {
            #main-title {
                display: none
            }

            .mui-textfield {
                padding-top: 0;
                margin-bottom: 10px
            }
        }

        .mui-appbar {
            background-color: #a040a0
        }

        .mui-textfield > input:focus, .mui-textfield > textarea:focus {
            border-color: #a040a0;
            background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBmaWxsPSIjYTA0MGEwIiBkPSJNMTUuNSAxNGgtLjc5bC0uMjgtLjI3QzE1LjQxIDEyLjU5IDE2IDExLjExIDE2IDkuNSAxNiA1LjkxIDEzLjA5IDMgOS41IDNTMyA1LjkxIDMgOS41IDUuOTEgMTYgOS41IDE2YzEuNjEgMCAzLjA5LS41OSA0LjIzLTEuNTdsLjI3LjI4di43OWw1IDQuOTlMMjAuNDkgMTlsLTQuOTktNXptLTYgMEM3LjAxIDE0IDUgMTEuOTkgNSA5LjVTNy4wMSA1IDkuNSA1IDE0IDcuMDEgMTQgOS41IDExLjk5IDE0IDkuNSAxNHoiLz48L3N2Zz4=)
        }

        .mui-textfield > input {
            background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48cGF0aCBmaWxsPSJyZ2JhKDAsMCwwLDAuNCkiIGQ9Ik0xNS41IDE0aC0uNzlsLS4yOC0uMjdDMTUuNDEgMTIuNTkgMTYgMTEuMTEgMTYgOS41IDE2IDUuOTEgMTMuMDkgMyA5LjUgM1MzIDUuOTEgMyA5LjUgNS45MSAxNiA5LjUgMTZjMS42MSAwIDMuMDktLjU5IDQuMjMtMS41N2wuMjcuMjh2Ljc5bDUgNC45OUwyMC40OSAxOWwtNC45OS01em0tNiAwQzcuMDEgMTQgNSAxMS45OSA1IDkuNVM3LjAxIDUgOS41IDUgMTQgNy4wMSAxNCA5LjUgMTEuOTkgMTQgOS41IDE0eiIvPjwvc3ZnPg==);
            background-repeat: no-repeat;
            background-position: right center;
            border-bottom: 1px solid rgba(0, 0, 0, .4)
        }

        .mui-textfield > input::-webkit-input-placeholder, .mui-textfield > textarea::-webkit-input-placeholder {
            color: rgba(0, 0, 0, .4)
        }

        .mui-textfield > input::-moz-placeholder, .mui-textfield > textarea::-moz-placeholder {
            color: rgba(0, 0, 0, .4)
        }

        .mui-textfield > input:-ms-input-placeholder, .mui-textfield > textarea:-ms-input-placeholder {
            color: rgba(0, 0, 0, .4)
        }

        .mui-textfield > input::placeholder, .mui-textfield > textarea::placeholder {
            color: rgba(0, 0, 0, .4)
        }

        #progress-mask {
            z-index: 20000;
            position: absolute;
            background-color: #fff;
            width: 100%;
            height: 100%;
            top: 0;
            -webkit-transition: opacity .1s;
            transition: opacity .1s;
            opacity: 0;
            pointer-events: none
        }

        #progress-mask.shown {
            opacity: .3
        }

        #monsters-list-wrapper {
            position: relative;
            text-align: center
        }

        #monsters-list {
            padding: 0
        }

        #monsters-list > li {
            margin: 10px;
            position: relative;
            width: 140px;
            height: 140px;
            overflow: hidden;
            display: -webkit-inline-box;
            display: -ms-inline-flexbox;
            display: inline-flex;
            list-style-type: none;
            cursor: pointer;
            z-index: 20000
        }

        #detail-view, #monsters-list li span, .detail-view-bg, .detail-view-fg, .mui-panel.detail-panel {
            position: absolute;
            bottom: 0
        }

        .detail-header, .detail-stats-row {
            display: -webkit-box;
            display: -ms-flexbox
        }

        @media (max-width: 767px) {
            #monsters-list li {
                margin: 5px
            }
        }

        @media (max-width: 320px) {
            #monsters-list > li {
                width: 110px;
                height: 110px
            }
        }

        @media (min-width: 992px) {
            #monsters-list > li {
                width: 160px;
                height: 160px
            }
        }

        #monsters-list .monster-sprite {
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 10;
            position: absolute;
            background-color: transparent;
            border: none
        }

        #monsters-list .monster-sprite:hover {
            background-color: rgba(255, 255, 255, .3)
        }

        #monsters-list .monster-sprite > .mui-ripple-effect {
            background-color: #fff
        }

        #monsters-list button.monster-sprite:active, #monsters-list button.monster-sprite:focus {
            background-color: initial;
            outline: 0
        }

        .monster-sprite {
            background-repeat: no-repeat;
            background-position: center center;
            width: 140px;
            height: 140px
        }

        @media (max-width: 320px) {
            .monster-sprite {
                width: 110px;
                height: 110px
            }
        }

        @media (min-width: 992px) {
            .monster-sprite {
                width: 160px;
                height: 160px
            }
        }

        #monsters-list li span {
            color: #fff;
            font-size: 14px;
            background-color: rgba(0, 0, 0, .5);
            left: 0;
            right: 0;
            z-index: 20;
            text-align: center;
            pointer-events: none
        }

        #detail-view {
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            color: #333
        }

        .detail-view-bg {
            top: 0;
            left: 0;
            right: 0;
            z-index: 2000
        }

        .detail-view-fg {
            top: 0;
            left: 0;
            right: 0;
            z-index: 3000
        }

        .detail-back-button {
            will-change: transform
        }

        .detail-back-button.animating {
            -webkit-transition: -webkit-transform .2s ease-in-out;
            transition: -webkit-transform .2s ease-in-out;
            transition: transform .2s ease-in-out;
            transition: transform .2s ease-in-out, -webkit-transform .2s ease-in-out
        }

        .back-button {
            background: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIzNiIgaGVpZ2h0PSIzNiIgdmlld0JveD0iMCAwIDM2IDM2Ij48cGF0aCBmaWxsPSIjZmZmIiBkPSJNMzAgMTYuNUgxMS43NGw4LjM4LTguMzhMMTggNiA2IDE4bDEyIDEyIDIuMTItMi4xMi04LjM4LTguMzhIMzB2LTN6Ii8+PC9zdmc+) no-repeat;
            width: 36px;
            height: 36px;
            border: none;
            margin: 10px
        }

        .back-button:focus {
            outline: 0
        }

        .mui-panel.detail-panel {
            will-change: transform;
            max-width: 600px;
            margin: 58px auto 10px;
            padding: 0;
            top: 0;
            left: 10px;
            right: 10px;
            overflow-y: scroll;
            -webkit-overflow-scrolling: touch
        }

        .detail-panel.animating {
            -webkit-transition: -webkit-transform .33333s cubic-bezier(0, 0, .21, 1);
            transition: -webkit-transform .33333s cubic-bezier(0, 0, .21, 1);
            transition: transform .33333s cubic-bezier(0, 0, .21, 1);
            transition: transform .33333s cubic-bezier(0, 0, .21, 1), -webkit-transform .33333s cubic-bezier(0, 0, .21, 1)
        }

        .detail-panel-header {
            max-width: 600px;
            margin: 0 auto;
            color: #fff;
            text-align: center;
            padding: 15px;
            font-size: 24px
        }

        .detail-panel-content {
            padding: 15px
        }

        .detail-sprite {
            will-change: opacity;
            width: 140px;
            height: 140px;
            min-width: 140px
        }

        @media (max-width: 320px) {
            .detail-sprite {
                width: 110px;
                height: 110px;
                min-width: 110px
            }
        }

        @media (min-width: 992px) {
            .detail-sprite {
                width: 160px;
                height: 160px;
                min-width: 160px
            }
        }

        .detail-header {
            display: flex
        }

        .detail-below-header {
            margin-top: 30px
        }

        .detail-national-id {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1;
            text-align: right;
            font-size: 30px
        }

        @media (max-width: 767px) {
            .detail-below-header {
                margin-top: 10px
            }

            .detail-national-id {
                font-size: 18px
            }
        }

        @media (max-width: 320px) {
            .detail-national-id {
                font-size: 14px
            }
        }

        .detail-infobox {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            margin-right: 10px
        }

        .detail-stats {
            min-width: 200px;
            max-width: 300px;
            margin-top: 10px;
            font-size: 14px
        }

        @media (max-width: 320px) {
            .detail-stats {
                font-size: 12px
            }
        }

        .detail-stats-row {
            display: flex;
            margin-top: 2px;
            margin-left: 2px
        }

        .detail-stats-row span:nth-child(even) {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -webkit-box-flex: 2;
            -ms-flex-positive: 2;
            flex-grow: 2;
            color: #fff
        }

        @media (max-width: 767px) {
            .detail-stats {
                min-width: 150px;
                max-width: 200px
            }

            .detail-stats-row span:nth-child(even) {
                -webkit-box-flex: 1;
                -ms-flex-positive: 1;
                flex-grow: 1
            }
        }

        .detail-stats-row span:nth-child(odd) {
            -ms-flex-preferred-size: 0;
            flex-basis: 0;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1
        }

        .stat-bar {
            position: relative;
            background: #ededed
        }

        .stat-bar-bg, .stat-bar-fg {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0
        }

        .stat-bar-bg {
            z-index: 100000;
            -webkit-transform-origin: left;
            transform-origin: left
        }

        .stat-bar-fg {
            z-index: 200000;
            margin-left: 2px
        }

        .detail-types-and-num {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center
        }

        .monster-sprite-facade.animating {
            -webkit-transition: -webkit-transform .33333s cubic-bezier(0, 0, .21, 1);
            transition: -webkit-transform .33333s cubic-bezier(0, 0, .21, 1);
            transition: transform .33333s cubic-bezier(0, 0, .21, 1);
            transition: transform .33333s cubic-bezier(0, 0, .21, 1), -webkit-transform .33333s cubic-bezier(0, 0, .21, 1)
        }

        #detail-view-container {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 3
        }

        .detail-view-bg {
            -webkit-transform-origin: 0 0;
            transform-origin: 0 0
        }

        .detail-view-bg.animating {
            -webkit-transition-property: -webkit-transform;
            transition-property: -webkit-transform;
            transition-property: transform;
            transition-property: transform, -webkit-transform;
            -webkit-transition-duration: .33333s;
            transition-duration: .33333s;
            -webkit-transition-timing-function: cubic-bezier(0, 0, .21, 1);
            transition-timing-function: cubic-bezier(0, 0, .21, 1)
        }

        .monster-type {
            color: #fff;
            border-radius: 2px;
            font-size: 16px;
            font-variant: small-caps;
            font-weight: 600;
            margin-right: 5px;
            padding: 1px 10px 5px
        }

        @media (max-width: 320px) {
            .monster-type {
                font-size: 13px;
                padding: 0 3px 2px
            }
        }

        .monster-description {
            font-size: 14px;
            margin: 0 20px 20px
        }

        @media (max-width: 320px) {
            .monster-description {
                font-size: 12px
            }
        }

        .hidden {
            display: none
        }

        .evolution-label, .evolution-row-inner {
            display: -webkit-box;
            display: -ms-flexbox;
            -ms-grid-column-align: center
        }

        .hover-shadow {
            border-radius: 50%;
            padding: 4px 5px
        }

        .monster-sprite-facade {
            will-change: transform;
            position: absolute;
            z-index: 99999990
        }

        .detail-subheader {
            font-size: 20px;
            color: #fff;
            padding-left: 10px;
            margin: 20px 0
        }

        .evolutions {
            margin: 0 20px
        }

        .evolution-row-inner {
            display: flex;
            flex-diration: horizontal;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            justify-items: center
        }

        .evolution-row .evolution-sprite {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1
        }

        .evolution-row svg {
            -ms-flex-negative: 1;
            flex-shrink: 1;
            -ms-flex-preferred-size: 25%;
            flex-basis: 25%
        }

        .evolution-label {
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            justify-items: center
        }

        .pokedex-modal, .toast {
            display: -webkit-box;
            display: -ms-flexbox
        }

        .evolution-label span {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            text-align: center
        }

        .arrow-right {
            width: 48px;
            height: 48px
        }

        .toast {
            position: fixed;
            z-index: 3;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, .8);
            text-align: center;
            color: #fff;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            font-size: 16px;
            will-change: opacity;
            -webkit-transition: opacity .5s ease-in-out;
            transition: opacity .5s ease-in-out;
            opacity: 0;
            display: flex
        }

        .toast button.mui-btn {
            margin: 6px 12px
        }

        .toast button.mui-btn:active {
            background-color: #ededed
        }

        .toast div, .toast span {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1
        }

        .big-spinner-holder, .pokedex-modal {
            -webkit-box-align: center;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0
        }

        .pokedex-modal {
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
            will-change: transform
        }

        .big-spinner-holder, .monster-minutia {
            display: -webkit-box;
            display: -ms-flexbox
        }

        .pokedex-modal.animating {
            -webkit-transition: .33333s -webkit-transform cubic-bezier(0, 0, .21, 1);
            transition: .33333s -webkit-transform cubic-bezier(0, 0, .21, 1);
            transition: .33333s transform cubic-bezier(0, 0, .21, 1);
            transition: .33333s transform cubic-bezier(0, 0, .21, 1), .33333s -webkit-transform cubic-bezier(0, 0, .21, 1)
        }

        .pokedex-modal-inner {
            max-width: 400px;
            margin: 0 20px
        }

        .pokedex-modal-inner.mui-panel {
            margin: 0 20px;
            padding: 0
        }

        .pokedex-modal-inner h2 {
            font-size: 18px;
            margin: 0;
            background: #a040a0;
            color: #fff;
            padding: 10px 16px
        }

        .pokedex-modal-content {
            font-size: 15px;
            margin: 25px 20px
        }

        .pokedex-modal-inner button {
            float: right
        }

        .sidedrawer-toggle.hover-shadow {
            padding: 4px 7px 5px 8px
        }

        .hover-shadow:active, .hover-shadow:focus, .hover-shadow:hover {
            background-color: rgba(0, 0, 0, .27)
        }

        .monster-species {
            font-size: 14px;
            font-weight: 700;
            margin: 10px 20px
        }

        .monster-minutia {
            display: flex;
            margin: 0 20px 10px
        }

        .monster-minutia > strong {
            -ms-flex-preferred-size: 20%;
            flex-basis: 20%;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1;
            margin-right: 3px;
            font-size: 14px
        }

        .monster-minutia > span {
            -ms-flex-preferred-size: 30%;
            flex-basis: 30%;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1;
            margin: 0 2px
        }

        @media (max-width: 320px) {
            .monster-minutia > span, .monster-minutia > strong {
                font-size: 12px
            }
        }

        @media (max-width: 767px) {
            .monster-minutia > span, .monster-minutia > strong {
                font-size: 13px
            }
        }

        .spinner {
            -webkit-transition: opacity .2s ease-in-out;
            transition: opacity .2s ease-in-out;
            opacity: 0;
            will-change: opacity;
            width: 24px;
            height: 24px;
            margin: 0 auto 5px
        }

        .shown .spinner {
            -webkit-animation: rotator 2.8s linear infinite;
            animation: rotator 2.8s linear infinite
        }

        .spinner.big-spinner {
            width: 48px;
            height: 48px;
            margin: 0;
            opacity: 1
        }

        .big-spinner-holder {
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            background: rgba(255, 255, 255, .2);
            opacity: 0;
            will-change: opacity;
            -webkit-transition: opacity .5s;
            transition: opacity .5s
        }

        .moves-inner-row, .when-attacked-row {
            display: -webkit-box;
            display: -ms-flexbox;
            -webkit-box-align: center
        }

        .big-spinner-holder.shown {
            opacity: 1
        }

        @-webkit-keyframes rotator {
            0% {
                -webkit-transform: rotate(0);
                transform: rotate(0);
                stroke: #A040A0
            }
            12.5% {
                -webkit-transform: rotate(90deg);
                transform: rotate(90deg);
                stroke: #A040A0
            }
            25% {
                -webkit-transform: rotate(180deg);
                transform: rotate(180deg);
                stroke: #78C850
            }
            37.5% {
                -webkit-transform: rotate(270deg);
                transform: rotate(270deg);
                stroke: #78C850
            }
            50% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
                stroke: #78C850
            }
            62.5% {
                -webkit-transform: rotate(450deg);
                transform: rotate(450deg);
                stroke: #78C850
            }
            75% {
                -webkit-transform: rotate(540deg);
                transform: rotate(540deg);
                stroke: #A040A0
            }
            87.5% {
                -webkit-transform: rotate(630deg);
                transform: rotate(630deg);
                stroke: #A040A0
            }
            100% {
                -webkit-transform: rotate(720deg);
                transform: rotate(720deg);
                stroke: #A040A0
            }
        }

        @keyframes rotator {
            0% {
                -webkit-transform: rotate(0);
                transform: rotate(0);
                stroke: #A040A0
            }
            12.5% {
                -webkit-transform: rotate(90deg);
                transform: rotate(90deg);
                stroke: #A040A0
            }
            25% {
                -webkit-transform: rotate(180deg);
                transform: rotate(180deg);
                stroke: #78C850
            }
            37.5% {
                -webkit-transform: rotate(270deg);
                transform: rotate(270deg);
                stroke: #78C850
            }
            50% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
                stroke: #78C850
            }
            62.5% {
                -webkit-transform: rotate(450deg);
                transform: rotate(450deg);
                stroke: #78C850
            }
            75% {
                -webkit-transform: rotate(540deg);
                transform: rotate(540deg);
                stroke: #A040A0
            }
            87.5% {
                -webkit-transform: rotate(630deg);
                transform: rotate(630deg);
                stroke: #A040A0
            }
            100% {
                -webkit-transform: rotate(720deg);
                transform: rotate(720deg);
                stroke: #A040A0
            }
        }

        .spinner-path {
            stroke-dasharray: 187;
            stroke-dashoffset: 93;
            -webkit-transform-origin: center;
            transform-origin: center
        }

        .when-attacked {
            margin: 10px 20px
        }

        .when-attacked-row {
            display: flex;
            -ms-flex-align: center;
            align-items: center
        }

        .when-attacked-row > span:nth-child(odd) {
            -ms-flex-preferred-size: 35%;
            flex-basis: 35%;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1
        }

        .when-attacked-row > span:nth-child(even) {
            -ms-flex-preferred-size: 15%;
            flex-basis: 15%;
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1;
            padding-left: 5px
        }

        @media (max-width: 320px) {
            .when-attacked-row > span:nth-child(even), .when-attacked-row > span:nth-child(odd) {
                font-size: 12px
            }
        }

        @media (min-width: 992px) {
            .when-attacked-row > span:nth-child(even), .when-attacked-row > span:nth-child(odd) {
                -ms-flex-preferred-size: 25%;
                flex-basis: 25%
            }
        }

        .monster-moves {
            padding: 0 20px
        }

        .moves-row {
            margin: 2px 0
        }

        .moves-inner-row {
            display: flex;
            -ms-flex-align: center;
            align-items: center;
            -ms-grid-column-align: center;
            justify-items: center;
            -webkit-box-orient: horizontal;
            -webkit-box-direction: normal;
            -ms-flex-direction: row;
            flex-direction: row
        }

        .moves-row-detail {
            margin: 10px 20px;
            background: #fafafa;
            padding: 10px
        }

        .moves-row-detail.animating {
            -webkit-transition: -webkit-transform .2s ease-in-out;
            transition: -webkit-transform .2s ease-in-out;
            transition: transform .2s ease-in-out;
            transition: transform .2s ease-in-out, -webkit-transform .2s ease-in-out;
            -webkit-transform-origin: center 0;
            transform-origin: center 0
        }

        .moves-row-stats {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -ms-grid-column-align: center;
            justify-items: center;
            margin-bottom: 10px
        }

        .moves-row-stats span {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1
        }

        .moves-inner-row > span:first-child {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1;
            -ms-flex-preferred-size: 10%;
            flex-basis: 10%
        }

        .moves-inner-row > span:nth-child(2) {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1;
            -ms-flex-preferred-size: 40%;
            flex-basis: 40%
        }

        .moves-inner-row > span:nth-child(3) {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1;
            -ms-flex-preferred-size: 30%;
            flex-basis: 30%
        }

        .moves-inner-row > span:nth-child(4) {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            -ms-flex-negative: 1;
            flex-shrink: 1;
            -ms-flex-preferred-size: 20%;
            flex-basis: 20%
        }

        @media (min-width: 768px) {
            .moves-inner-row > span:first-child {
                -ms-flex-preferred-size: 15%;
                flex-basis: 15%
            }

            .moves-inner-row > span:nth-child(2) {
                -ms-flex-preferred-size: 50%;
                flex-basis: 50%
            }

            .moves-inner-row > span:nth-child(3) {
                -ms-flex-preferred-size: 20%;
                flex-basis: 20%
            }

            .moves-inner-row > span:nth-child(4) {
                -ms-flex-preferred-size: 15%;
                flex-basis: 15%
            }
        }

        .dropdown-button {
            position: relative;
            width: 36px;
            height: 30px;
            background-color: #fff;
            border: none
        }

        .dropdown-button-image {
            background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0OCIgaGVpZ2h0PSI0OCIgdmlld0JveD0iMCAwIDQ4IDQ4Ij4KICAgIDxwYXRoIGQ9Ik0xNy4xNyAzMi45Mmw5LjE3LTkuMTctOS4xNy05LjE3TDIwIDExLjc1bDEyIDEyLTEyIDEyeiIgZmlsbD0iIzMzMyIvPgogICAgPHBhdGggZD0iTTAtLjI1aDQ4djQ4SDB6IiBmaWxsPSJub25lIi8+Cjwvc3ZnPgo=);
            background-position: center center;
            background-repeat: no-repeat;
            background-size: 24px 24px;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            top: 0
        }

        .dropdown-button-image.animating {
            -webkit-transition: -webkit-transform .2s linear;
            transition: -webkit-transform .2s linear;
            transition: transform .2s linear;
            transition: transform .2s linear, -webkit-transform .2s linear
        }

        button.dropdown-button:active, button.dropdown-button:focus {
            background-color: #fff;
            outline: 0
        }

        button.dropdown-button:hover {
            background-color: #fafafa
        }

        .sr-only {
            position: absolute;
            left: -10000px;
            top: auto;
            width: 1px;
            height: 1px;
            overflow: hidden
        }

        .sprite-1 {
            background-image: url(data:image/png;base64,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)
        }

        .sprite-2 {
            background-image: url(data:image/png;base64,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)
        }

        .sprite-3 {
            background-image: url(data:image/png;base64,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)
        }

        .sprite-4 {
            background-image: url(data:image/png;base64,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)
        }

        .sprite-5 {
            background-image: url(data:image/png;base64,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)
        }

        .sprite-6 {
            background-image: url(data:image/png;base64,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)
        }

        .sprite-7 {
            background-image: url(data:image/png;base64,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)
        }

        .sprite-8 {
            background-image: url(data:image/png;base64,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)
        }</style>
    <link rel=icon href=favicon.ico type=image/x-icon>
    <link rel=manifest href=manifest.json>
    <link rel=apple-touch-icon href=img/icon-48.png>
    <link rel=apple-touch-icon sizes=96x96 href=img/icon-96.png>
    <link rel=apple-touch-icon sizes=196x196 href=img/icon-196.png>

	</head>
<body>
<header id=header>
    <div class="mui-appbar mui--appbar-line-height">
        <div class=mui-container-fluid>
            <a class="sidedrawer-toggle mui--visible-xs-inline-block js-show-sidedrawer hover-shadow">
                <svg id=Layer_1 xmlns=http://www.w3.org/2000/svg viewBox="0 0 17 23" width=17 height=23>
                    <style>.st0 {
                            fill: #fff;
                        }</style>
                    <path class=st0 d="M0 10.5h17v2H0zM0 5h17v2H0zM0 16h17v2H0z"/>
                </svg>
            </a>
            <a class="sidedrawer-toggle mui--hidden-xs js-hide-sidedrawer hover-shadow">
                <svg id=Layer_1 xmlns=http://www.w3.org/2000/svg viewBox="0 0 17 23" width=17 height=23>
                    <style>.st0 {
                            fill: #fff;
                        }</style>
                    <path class=st0 d="M0 10.5h17v2H0zM0 5h17v2H0zM0 16h17v2H0z"/>
                </svg>
            </a>
            <span class="mui--text-title mui--visible-xs-inline-block title">Pokedex.org</span>
        </div>
    </div>
</header>
<div id=sidedrawer class=mui--no-user-select>
    <div id=sidedrawer-brand class="mui--appbar-line-height mui--text-title">
        Pokedex.org
    </div>
    <div class=mui-divider></div>
    <ul>
        <li>
            <a href=# id=pokemon-link>
                <strong>Pok&eacute;mon</strong>
            </a>
        </li>
        <li>
            <a href=https://github.com/nolanlawson/pokedex.org#readme target=_blank>
                <strong>About</strong>
            </a>
        </li>
    </ul>
</div>
<div id=content-wrapper>
    <div class=mui--appbar-height></div>
    <div class=mui-container-fluid>
        <br>

        <h1 id=main-title>Pokedex.org</h1>

        <div class=mui-textfield>
            <input id=monsters-search-bar type=search placeholder="Search for Pokémon" spellcheck=false autocorrect=off
                   autocomplete=off>
            <label for=monsters-search-bar class=sr-only>Search for Pokémon</label>
        </div>


        <div id=monsters-list-wrapper>
            <ul id=monsters-list>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-1"></button>
                    <span>Bulbasaur</span></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-2"></button>
                    <span>Ivysaur</span></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-3"></button>
                    <span>Venusaur</span></li>
                <li style="background: #F08030">
                    <button type=button class="monster-sprite sprite-4"></button>
                    <span>Charmander</span></li>
                <li style="background: #F08030">
                    <button type=button class="monster-sprite sprite-5"></button>
                    <span>Charmeleon</span></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #A890F0 50%)">
                    <button type=button class="monster-sprite sprite-6"></button>
                    <span>Charizard</span></li>
                <li style="background: #6890F0">
                    <button type=button class="monster-sprite sprite-7"></button>
                    <span>Squirtle</span></li>
                <li style="background: #6890F0">
                    <button type=button class="monster-sprite sprite-8"></button>
                    <span>Wartortle</span></li>
                <li style="background: #6890F0">
                    <button type=button class="monster-sprite sprite-9"></button>
                    <span>Blastoise</span></li>
                <li style="background: #A8B820">
                    <button type=button class="monster-sprite sprite-10"></button>
                    <span>Caterpie</span></li>
                <li style="background: #A8B820">
                    <button type=button class="monster-sprite sprite-11"></button>
                    <span>Metapod</span></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)">
                    <button type=button class="monster-sprite sprite-12"></button>
                    <span>Butterfree</span></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-13"></button>
                    <span>Weedle</span></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-14"></button>
                    <span>Kakuna</span></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-15"></button>
                    <span>Beedrill</span></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)">
                    <button type=button class="monster-sprite sprite-16"></button>
                    <span>Pidgey</span></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)">
                    <button type=button class="monster-sprite sprite-17"></button>
                    <span>Pidgeotto</span></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)">
                    <button type=button class="monster-sprite sprite-18"></button>
                    <span>Pidgeot</span></li>
                <li style="background: #A8A878">
                    <button type=button class="monster-sprite sprite-19"></button>
                    <span>Rattata</span></li>
                <li style="background: #A8A878">
                    <button type=button class="monster-sprite sprite-20"></button>
                    <span>Raticate</span></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)">
                    <button type=button class="monster-sprite sprite-21"></button>
                    <span>Spearow</span></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)">
                    <button type=button class="monster-sprite sprite-22"></button>
                    <span>Fearow</span></li>
                <li style="background: #A040A0">
                    <button type=button class="monster-sprite sprite-23"></button>
                    <span>Ekans</span></li>
                <li style="background: #A040A0">
                    <button type=button class="monster-sprite sprite-24"></button>
                    <span>Arbok</span></li>
                <li style="background: #F8D030">
                    <button type=button class="monster-sprite sprite-25"></button>
                    <span>Pikachu</span></li>
                <li style="background: #F8D030">
                    <button type=button class="monster-sprite sprite-26"></button>
                    <span>Raichu</span></li>
                <li style="background: #E0C068">
                    <button type=button class="monster-sprite sprite-27"></button>
                    <span>Sandshrew</span></li>
                <li style="background: #E0C068">
                    <button type=button class="monster-sprite sprite-28"></button>
                    <span>Sandslash</span></li>
                <li style="background: #A040A0">
                    <button type=button class="monster-sprite sprite-29"></button>
                    <span>Nidoran ♀</span></li>
                <li style="background: #A040A0">
                    <button type=button class="monster-sprite sprite-30"></button>
                    <span>Nidorina</span></li>
                <li style="background: linear-gradient(90deg, #E0C068 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-31"></button>
                    <span>Nidoqueen</span></li>
                <li style="background: #A040A0">
                    <button type=button class="monster-sprite sprite-32"></button>
                    <span>Nidoran ♂</span></li>
                <li style="background: #A040A0">
                    <button type=button class="monster-sprite sprite-33"></button>
                    <span>Nidorino</span></li>
                <li style="background: linear-gradient(90deg, #E0C068 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-34"></button>
                    <span>Nidoking</span></li>
                <li style="background: #EE99AC">
                    <button type=button class="monster-sprite sprite-35"></button>
                    <span>Clefairy</span></li>
                <li style="background: #EE99AC">
                    <button type=button class="monster-sprite sprite-36"></button>
                    <span>Clefable</span></li>
                <li style="background: #F08030">
                    <button type=button class="monster-sprite sprite-37"></button>
                    <span>Vulpix</span></li>
                <li style="background: #F08030">
                    <button type=button class="monster-sprite sprite-38"></button>
                    <span>Ninetales</span></li>
                <li style="background: linear-gradient(90deg, #EE99AC 50%, #A8A878 50%)">
                    <button type=button class="monster-sprite sprite-39"></button>
                    <span>Jigglypuff</span></li>
                <li style="background: linear-gradient(90deg, #EE99AC 50%, #A8A878 50%)">
                    <button type=button class="monster-sprite sprite-40"></button>
                    <span>Wigglytuff</span></li>
                <li style="background: linear-gradient(90deg, #A040A0 50%, #A890F0 50%)">
                    <button type=button class="monster-sprite sprite-41"></button>
                    <span>Zubat</span></li>
                <li style="background: linear-gradient(90deg, #A040A0 50%, #A890F0 50%)">
                    <button type=button class="monster-sprite sprite-42"></button>
                    <span>Golbat</span></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-43"></button>
                    <span>Oddish</span></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-44"></button>
                    <span>Gloom</span></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-45"></button>
                    <span>Vileplume</span></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A8B820 50%)">
                    <button type=button class="monster-sprite sprite-46"></button>
                    <span>Paras</span></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A8B820 50%)">
                    <button type=button class="monster-sprite sprite-47"></button>
                    <span>Parasect</span></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-48"></button>
                    <span>Venonat</span></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-49"></button>
                    <span>Venomoth</span></li>
                <li style="background: #E0C068">
                    <button type=button class="monster-sprite sprite-50"></button>
                    <span>Diglett</span></li>
                <li style="background: #E0C068">
                    <button type=button class="monster-sprite sprite-51"></button>
                    <span>Dugtrio</span></li>
                <li style="background: #A8A878">
                    <button type=button class="monster-sprite sprite-52"></button>
                    <span>Meowth</span></li>
                <li style="background: #A8A878">
                    <button type=button class="monster-sprite sprite-53"></button>
                    <span>Persian</span></li>
                <li style="background: #6890F0">
                    <button type=button class="monster-sprite sprite-54"></button>
                    <span>Psyduck</span></li>
                <li style="background: #6890F0">
                    <button type=button class="monster-sprite sprite-55"></button>
                    <span>Golduck</span></li>
                <li style="background: #C03028">
                    <button type=button class="monster-sprite sprite-56"></button>
                    <span>Mankey</span></li>
                <li style="background: #C03028">
                    <button type=button class="monster-sprite sprite-57"></button>
                    <span>Primeape</span></li>
                <li style="background: #F08030">
                    <button type=button class="monster-sprite sprite-58"></button>
                    <span>Growlithe</span></li>
                <li style="background: #F08030">
                    <button type=button class="monster-sprite sprite-59"></button>
                    <span>Arcanine</span></li>
                <li style="background: #6890F0">
                    <button type=button class="monster-sprite sprite-60"></button>
                    <span>Poliwag</span></li>
                <li style="background: #6890F0">
                    <button type=button class="monster-sprite sprite-61"></button>
                    <span>Poliwhirl</span></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #C03028 50%)">
                    <button type=button class="monster-sprite sprite-62"></button>
                    <span>Poliwrath</span></li>
                <li style="background: #F85888">
                    <button type=button class="monster-sprite sprite-63"></button>
                    <span>Abra</span></li>
                <li style="background: #F85888">
                    <button type=button class="monster-sprite sprite-64"></button>
                    <span>Kadabra</span></li>
                <li style="background: #F85888">
                    <button type=button class="monster-sprite sprite-65"></button>
                    <span>Alakazam</span></li>
                <li style="background: #C03028">
                    <button type=button class="monster-sprite sprite-66"></button>
                    <span>Machop</span></li>
                <li style="background: #C03028">
                    <button type=button class="monster-sprite sprite-67"></button>
                    <span>Machoke</span></li>
                <li style="background: #C03028">
                    <button type=button class="monster-sprite sprite-68"></button>
                    <span>Machamp</span></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-69"></button>
                    <span>Bellsprout</span></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-70"></button>
                    <span>Weepinbell</span></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-71"></button>
                    <span>Victreebel</span></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-72"></button>
                    <span>Tentacool</span></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-73"></button>
                    <span>Tentacruel</span></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #E0C068 50%)">
                    <button type=button class="monster-sprite sprite-74"></button>
                    <span>Geodude</span></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #E0C068 50%)">
                    <button type=button class="monster-sprite sprite-75"></button>
                    <span>Graveler</span></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #E0C068 50%)">
                    <button type=button class="monster-sprite sprite-76"></button>
                    <span>Golem</span></li>
                <li style="background: #F08030">
                    <button type=button class="monster-sprite sprite-77"></button>
                    <span>Ponyta</span></li>
                <li style="background: #F08030">
                    <button type=button class="monster-sprite sprite-78"></button>
                    <span>Rapidash</span></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #6890F0 50%)">
                    <button type=button class="monster-sprite sprite-79"></button>
                    <span>Slowpoke</span></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #6890F0 50%)">
                    <button type=button class="monster-sprite sprite-80"></button>
                    <span>Slowbro</span></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #B8B8D0 50%)">
                    <button type=button class="monster-sprite sprite-81"></button>
                    <span>Magnemite</span></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #B8B8D0 50%)">
                    <button type=button class="monster-sprite sprite-82"></button>
                    <span>Magneton</span></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)">
                    <button type=button class="monster-sprite sprite-83"></button>
                    <span>Farfetch&#39;d</span></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)">
                    <button type=button class="monster-sprite sprite-84"></button>
                    <span>Doduo</span></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)">
                    <button type=button class="monster-sprite sprite-85"></button>
                    <span>Dodrio</span></li>
                <li style="background: #6890F0">
                    <button type=button class="monster-sprite sprite-86"></button>
                    <span>Seel</span></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #6890F0 50%)">
                    <button type=button class="monster-sprite sprite-87"></button>
                    <span>Dewgong</span></li>
                <li style="background: #A040A0">
                    <button type=button class="monster-sprite sprite-88"></button>
                    <span>Grimer</span></li>
                <li style="background: #A040A0">
                    <button type=button class="monster-sprite sprite-89"></button>
                    <span>Muk</span></li>
                <li style="background: #6890F0">
                    <button type=button class="monster-sprite sprite-90"></button>
                    <span>Shellder</span></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #6890F0 50%)">
                    <button type=button class="monster-sprite sprite-91"></button>
                    <span>Cloyster</span></li>
                <li style="background: linear-gradient(90deg, #705898 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-92"></button>
                    <span>Gastly</span></li>
                <li style="background: linear-gradient(90deg, #705898 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-93"></button>
                    <span>Haunter</span></li>
                <li style="background: linear-gradient(90deg, #705898 50%, #A040A0 50%)">
                    <button type=button class="monster-sprite sprite-94"></button>
                    <span>Gengar</span></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #E0C068 50%)">
                    <button type=button class="monster-sprite sprite-95"></button>
                    <span>Onix</span></li>
                <li style="background: #F85888">
                    <button type=button class="monster-sprite sprite-96"></button>
                    <span>Drowzee</span></li>
                <li style="background: #F85888">
                    <button type=button class="monster-sprite sprite-97"></button>
                    <span>Hypno</span></li>
                <li style="background: #6890F0">
                    <button type=button class="monster-sprite sprite-98"></button>
                    <span>Krabby</span></li>
                <li style="background: #6890F0">
                    <button type=button class="monster-sprite sprite-99"></button>
                    <span>Kingler</span></li>
                <li style="background: #F8D030">
                    <button type=button class="monster-sprite sprite-100"></button>
                    <span>Voltorb</span></li>
                <li style="background: #F8D030"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #78C850 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #78C850 50%)"></li>
                <li style="background: #E0C068"></li>
                <li style="background: #E0C068"></li>
                <li style="background: #C03028"></li>
                <li style="background: #C03028"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A040A0"></li>
                <li style="background: #A040A0"></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #E0C068 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #78C850"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #6890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #EE99AC 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #98D8D8 50%)"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F08030"></li>
                <li style="background: #A8B820"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #6890F0 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F08030"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #A890F0 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #A890F0 50%)"></li>
                <li style="background: #7038F8"></li>
                <li style="background: #7038F8"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #A890F0 50%)"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: #78C850"></li>
                <li style="background: #78C850"></li>
                <li style="background: #78C850"></li>
                <li style="background: #F08030"></li>
                <li style="background: #F08030"></li>
                <li style="background: #F08030"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #A040A0 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #6890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #6890F0 50%)"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #EE99AC"></li>
                <li style="background: linear-gradient(90deg, #EE99AC 50%, #A8A878 50%)"></li>
                <li style="background: #EE99AC"></li>
                <li style="background: linear-gradient(90deg, #EE99AC 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #A890F0 50%)"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #78C850"></li>
                <li style="background: linear-gradient(90deg, #EE99AC 50%, #6890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #EE99AC 50%, #6890F0 50%)"></li>
                <li style="background: #B8A038"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A890F0 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #78C850"></li>
                <li style="background: #78C850"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #E0C068 50%)"></li>
                <li style="background: #F85888"></li>
                <li style="background: #705848"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #6890F0 50%)"></li>
                <li style="background: #705898"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #A8A878 50%)"></li>
                <li style="background: #A8B820"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #A8B820 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #E0C068 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #E0C068 50%)"></li>
                <li style="background: #EE99AC"></li>
                <li style="background: #EE99AC"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #98D8D8 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #F08030"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #B8A038 50%)"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #F08030 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #F08030 50%)"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #6890F0 50%)"></li>
                <li style="background: #E0C068"></li>
                <li style="background: #E0C068"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #C03028"></li>
                <li style="background: #C03028"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #98D8D8 50%)"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F08030"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F08030"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #78C850 50%)"></li>
                <li style="background: #78C850"></li>
                <li style="background: #78C850"></li>
                <li style="background: #78C850"></li>
                <li style="background: #F08030"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #C03028 50%)"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #E0C068 50%)"></li>
                <li style="background: #705848"></li>
                <li style="background: #705848"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8B820"></li>
                <li style="background: #A8B820"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)"></li>
                <li style="background: #A8B820"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #6890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #6890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #6890F0 50%)"></li>
                <li style="background: #78C850"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #78C850 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #78C850 50%)"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #EE99AC 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #EE99AC 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #EE99AC 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)"></li>
                <li style="background: #78C850"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #C03028 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #705898 50%, #A8B820 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #C03028"></li>
                <li style="background: #C03028"></li>
                <li style="background: linear-gradient(90deg, #EE99AC 50%, #A8A878 50%)"></li>
                <li style="background: #B8A038"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #705898 50%)"></li>
                <li style="background: linear-gradient(90deg, #EE99AC 50%, #B8B8D0 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #C03028 50%)"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #A8B820"></li>
                <li style="background: #A8B820"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)"></li>
                <li style="background: #A040A0"></li>
                <li style="background: #A040A0"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #6890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #6890F0 50%)"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #E0C068 50%)"></li>
                <li style="background: #F08030"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #E0C068"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #E0C068 50%)"></li>
                <li style="background: #78C850"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #78C850 50%)"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #A890F0 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A040A0"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #E0C068 50%)"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #6890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #B8A038 50%)"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #705898"></li>
                <li style="background: #705898"></li>
                <li style="background: #705898"></li>
                <li style="background: #705898"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A890F0 50%)"></li>
                <li style="background: #F85888"></li>
                <li style="background: #705848"></li>
                <li style="background: #F85888"></li>
                <li style="background: #98D8D8"></li>
                <li style="background: #98D8D8"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #6890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #6890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #6890F0 50%)"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #B8A038 50%)"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #7038F8"></li>
                <li style="background: #7038F8"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #B8B8D0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #B8B8D0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #B8B8D0 50%)"></li>
                <li style="background: #B8A038"></li>
                <li style="background: #98D8D8"></li>
                <li style="background: #B8B8D0"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #7038F8 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #7038F8 50%)"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #E0C068"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #B8B8D0 50%)"></li>
                <li style="background: #F85888"></li>
                <li style="background: #78C850"></li>
                <li style="background: #78C850"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #E0C068 50%)"></li>
                <li style="background: #F08030"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #C03028 50%)"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #B8B8D0 50%)"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A8A878 50%)"></li>
                <li style="background: #A8B820"></li>
                <li style="background: #A8B820"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F8D030"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)"></li>
                <li style="background: #B8A038"></li>
                <li style="background: #B8A038"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #B8A038 50%)"></li>
                <li style="background: #A8B820"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #78C850"></li>
                <li style="background: #78C850"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #E0C068 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #705898 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #705898 50%, #A890F0 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #705898"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #A890F0 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #F85888"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #B8B8D0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #B8B8D0 50%)"></li>
                <li style="background: #B8A038"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #EE99AC 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #705898 50%)"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #E0C068 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #C03028"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #C03028 50%)"></li>
                <li style="background: #E0C068"></li>
                <li style="background: #E0C068"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #A040A0 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #A040A0 50%, #C03028 50%)"></li>
                <li style="background: #78C850"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #78C850 50%)"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #78C850 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #98D8D8 50%)"></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #B8B8D0 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #E0C068 50%)"></li>
                <li style="background: #78C850"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F08030"></li>
                <li style="background: linear-gradient(90deg, #EE99AC 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A890F0 50%)"></li>
                <li style="background: #78C850"></li>
                <li style="background: #98D8D8"></li>
                <li style="background: linear-gradient(90deg, #E0C068 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #E0C068 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #B8A038 50%)"></li>
                <li style="background: #705898"></li>
                <li style="background: linear-gradient(90deg, #98D8D8 50%, #705898 50%)"></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #705898 50%)"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #B8B8D0 50%)"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #6890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #B8B8D0 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #705898 50%)"></li>
                <li style="background: #F85888"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #705848"></li>
                <li style="background: #78C850"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #F08030 50%)"></li>
                <li style="background: #78C850"></li>
                <li style="background: #78C850"></li>
                <li style="background: #78C850"></li>
                <li style="background: #F08030"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #C03028 50%)"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #705848"></li>
                <li style="background: #705848"></li>
                <li style="background: #78C850"></li>
                <li style="background: #78C850"></li>
                <li style="background: #F08030"></li>
                <li style="background: #F08030"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #6890F0"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #B8A038"></li>
                <li style="background: #B8A038"></li>
                <li style="background: #B8A038"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #A890F0 50%)"></li>
                <li style="background: #E0C068"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #E0C068 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #C03028"></li>
                <li style="background: #C03028"></li>
                <li style="background: #C03028"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #E0C068 50%)"></li>
                <li style="background: #C03028"></li>
                <li style="background: #C03028"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #EE99AC 50%, #78C850 50%)"></li>
                <li style="background: linear-gradient(90deg, #EE99AC 50%, #78C850 50%)"></li>
                <li style="background: #78C850"></li>
                <li style="background: #78C850"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #E0C068 50%)"></li>
                <li style="background: #F08030"></li>
                <li style="background: #F08030"></li>
                <li style="background: #78C850"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #A8B820 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #A890F0 50%)"></li>
                <li style="background: #705898"></li>
                <li style="background: #705898"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #B8A038 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #A890F0 50%)"></li>
                <li style="background: #A040A0"></li>
                <li style="background: #A040A0"></li>
                <li style="background: #705848"></li>
                <li style="background: #705848"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #A8A878"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #A890F0 50%)"></li>
                <li style="background: #98D8D8"></li>
                <li style="background: #98D8D8"></li>
                <li style="background: #98D8D8"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #A890F0 50%)"></li>
                <li style="background: #A8B820"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #705898 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #705898 50%)"></li>
                <li style="background: #6890F0"></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #B8B8D0 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #B8B8D0 50%)"></li>
                <li style="background: #B8B8D0"></li>
                <li style="background: #B8B8D0"></li>
                <li style="background: #B8B8D0"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F8D030"></li>
                <li style="background: #F85888"></li>
                <li style="background: #F85888"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #705898 50%)"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #705898 50%)"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #705898 50%)"></li>
                <li style="background: #7038F8"></li>
                <li style="background: #7038F8"></li>
                <li style="background: #7038F8"></li>
                <li style="background: #98D8D8"></li>
                <li style="background: #98D8D8"></li>
                <li style="background: #98D8D8"></li>
                <li style="background: #A8B820"></li>
                <li style="background: #A8B820"></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #E0C068 50%)"></li>
                <li style="background: #C03028"></li>
                <li style="background: #C03028"></li>
                <li style="background: #7038F8"></li>
                <li style="background: linear-gradient(90deg, #705898 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #705898 50%, #E0C068 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #B8B8D0 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #B8B8D0 50%)"></li>
                <li style="background: #A8A878"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #A890F0 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #A890F0 50%)"></li>
                <li style="background: #F08030"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #7038F8 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #7038F8 50%)"></li>
                <li style="background: linear-gradient(90deg, #705848 50%, #7038F8 50%)"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #F08030 50%, #A8B820 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8A038 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #78C850 50%, #C03028 50%)"></li>
                <li style="background: #A890F0"></li>
                <li style="background: linear-gradient(90deg, #F8D030 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #F08030 50%)"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #F8D030 50%)"></li>
                <li style="background: linear-gradient(90deg, #E0C068 50%, #A890F0 50%)"></li>
                <li style="background: linear-gradient(90deg, #7038F8 50%, #98D8D8 50%)"></li>
                <li style="background: linear-gradient(90deg, #6890F0 50%, #C03028 50%)"></li>
                <li style="background: linear-gradient(90deg, #F85888 50%, #A8A878 50%)"></li>
                <li style="background: linear-gradient(90deg, #B8B8D0 50%, #A8B820 50%)"></li>
            </ul>
            <div id=progress-mask></div>
        </div>
    </div>
</div>
<footer id=footer>
    <div class=mui-container-fluid>
        <br>
        An <a href=https://github.com/nolanlawson/pokedex.org>open-source site</a>
        by <a href=http://nolanlawson.com>Nolan Lawson</a>,
        with help from <a href=http://pokeapi.co/ >Pokéapi</a>.
        <br>
        All content is &copy; Nintendo, Game Freak, and The Pokémon Company.
    </div>
</footer>
<div id=detail-view-container class=hidden>
    <div id=detail-view>
        <div style="background: linear-gradient(90deg, #78C850 50%, #A040A0 50%)" class=detail-view-bg>
            <div class=big-spinner-holder>
                <svg width=65px height=65px viewbox="0 0 66 66" xmlns=http://www.w3.org/2000/svg
                     class="spinner big-spinner">
                    <circle fill=none stroke-width=6 stroke-linecap=round cx=33 cy=33 r=30 class=spinner-path></circle>
                </svg>
            </div>
        </div>
        <div class=detail-view-fg>
            <button type=button class="back-button detail-back-button hover-shadow"></button>
            <div class="mui-panel detail-panel"><h1 style="background: rgb(76, 140, 44)" class=detail-panel-header>
                    Bulbasaur</h1>
                <div class=detail-panel-content>
                    <div class=detail-header>
                        <div class="detail-sprite monster-sprite sprite-1"></div>
                        <div class=detail-infobox>
                            <div class=detail-types-and-num>
                                <div class=detail-types><span
                                            style="border: 1px solid rgb(186, 227, 166); background: rgb(105, 194, 61)"
                                            class=monster-type>grass</span><span
                                            style="border: 1px solid rgb(200, 116, 200); background: rgb(146, 58, 146)"
                                            class=monster-type>poison</span></div>
                                <div class=detail-national-id><span>#1</span></div>
                            </div>
                            <div class=detail-stats>
                                <div class=detail-stats-row><span>HP</span><span class=stat-bar><div
                                                style="background: rgb(105, 194, 61); transform: scaleX(0.3217993079584775)"
                                                class=stat-bar-bg></div><div style="color: #fff"
                                                                             class=stat-bar-fg>45</div></span></div>
                                <div class=detail-stats-row><span>Attack</span><span class=stat-bar><div
                                                style="background: rgb(105, 194, 61); transform: scaleX(0.34738946559015765)"
                                                class=stat-bar-bg></div><div style="color: #fff"
                                                                             class=stat-bar-fg>49</div></span></div>
                                <div class=detail-stats-row><span>Defense</span><span class=stat-bar><div
                                                style="background: rgb(105, 194, 61); transform: scaleX(0.34738946559015765)"
                                                class=stat-bar-bg></div><div style="color: #fff"
                                                                             class=stat-bar-fg>49</div></span></div>
                                <div class=detail-stats-row><span>Speed</span><span class=stat-bar><div
                                                style="background: rgb(105, 194, 61); transform: scaleX(0.3217993079584775)"
                                                class=stat-bar-bg></div><div style="color: #fff"
                                                                             class=stat-bar-fg>45</div></span></div>
                                <div class=detail-stats-row><span>Sp Atk</span><span class=stat-bar><div
                                                style="background: rgb(105, 194, 61); transform: scaleX(0.4448289119569397)"
                                                class=stat-bar-bg></div><div style="color: #fff"
                                                                             class=stat-bar-fg>65</div></span></div>
                                <div class=detail-stats-row><span>Sp Def</span><span class=stat-bar><div
                                                style="background: rgb(105, 194, 61); transform: scaleX(0.4448289119569397)"
                                                class=stat-bar-bg></div><div style="color: #fff"
                                                                             class=stat-bar-fg>65</div></span></div>
                            </div>
                        </div>
                    </div>
                    <div class=detail-below-header>
                        <div class=monster-species>Seed Pokémon</div>
                        <div class=monster-description>For some time after its birth, it grows by gaining nourishment
                            from the seed on its back.
                        </div>
                        <h2 style="background: rgb(76, 140, 44)" class=detail-subheader>Profile</h2>
                        <div class=monster-minutia>
                            <strong>Height:</strong><span>0.7 m</span><strong>Weight:</strong><span>6.9 kg</span></div>
                        <div class=monster-minutia><strong>Catch Rate:</strong><span>0%</span><strong>Gender
                                Ratio:</strong><span>87.5% ♂ 12.5% ♀</span></div>
                        <div class=monster-minutia><strong>Egg Groups:</strong><span></span><strong>Hatch
                                Steps:</strong><span>5100</span></div>
                        <div class=monster-minutia><strong>Abilities:</strong><span>Chlorophyll, Overgrow</span><strong>EVs:</strong><span>1 Sp Att</span>
                        </div>
                        <h2 style="background: rgb(76, 140, 44)" class=detail-subheader>Damage When Attacked</h2>
                        <div class=when-attacked>
                            <div class=when-attacked-row><span
                                        style="border: 1px solid rgb(255, 255, 255); background: rgb(126, 206, 206)"
                                        class=monster-type>ice</span><span style="color: rgb(237, 109, 18)"
                                                                           class=monster-multiplier>2x</span><span
                                        style="border: 1px solid rgb(252, 234, 161); background: rgb(246, 201, 19)"
                                        class=monster-type>electric</span><span style="color: rgb(69, 120, 237)"
                                                                                class=monster-multiplier>0.5x</span>
                            </div>
                            <div class=when-attacked-row><span
                                        style="border: 1px solid rgb(246, 237, 213); background: rgb(219, 181, 77)"
                                        class=monster-type>ground</span><span style="color: rgb(237, 109, 18)"
                                                                              class=monster-multiplier>2x</span><span
                                        style="border: 1px solid rgb(255, 255, 255); background: rgb(232, 120, 144)"
                                        class=monster-type>fairy</span><span style="color: rgb(69, 120, 237)"
                                                                             class=monster-multiplier>0.5x</span></div>
                            <div class=when-attacked-row><span
                                        style="border: 1px solid rgb(253, 216, 227); background: rgb(247, 54, 112)"
                                        class=monster-type>psychic</span><span style="color: rgb(237, 109, 18)"
                                                                               class=monster-multiplier>2x</span><span
                                        style="border: 1px solid rgb(227, 235, 252); background: rgb(69, 120, 237)"
                                        class=monster-type>water</span><span style="color: rgb(69, 120, 237)"
                                                                             class=monster-multiplier>0.5x</span></div>
                            <div class=when-attacked-row><span
                                        style="border: 1px solid rgb(247, 191, 151); background: rgb(237, 109, 18)"
                                        class=monster-type>fire</span><span style="color: rgb(237, 109, 18)"
                                                                            class=monster-multiplier>2x</span><span
                                        style="border: 1px solid rgb(223, 105, 98); background: rgb(174, 42, 36)"
                                        class=monster-type>fighting</span><span style="color: rgb(69, 120, 237)"
                                                                                class=monster-multiplier>0.5x</span>
                            </div>
                            <div class=when-attacked-row><span
                                        style="border: 1px solid rgb(255, 255, 255); background: rgb(142, 111, 235)"
                                        class=monster-type>flying</span><span style="color: rgb(237, 109, 18)"
                                                                              class=monster-multiplier>2x</span><span
                                        style="border: 1px solid rgb(186, 227, 166); background: rgb(105, 194, 61)"
                                        class=monster-type>grass</span><span style="color: rgb(20, 75, 204)"
                                                                             class=monster-multiplier>0.25x</span></div>
                        </div>
                        <h2 style="background: rgb(76, 140, 44)" class=detail-subheader>Evolutions</h2>
                        <div class=evolutions>
                            <div class=evolution-row>
                                <div class=evolution-row-inner>
                                    <div class="evolution-sprite monster-sprite sprite-1"></div>
                                    <svg style="fill: rgb(76, 140, 44); stroke: rgb(76, 140, 44)"
                                         xmlns=http://www.w3.org/2000/svg width=48 height=48 viewBox="0 0 48 48">
                                        <path d="M24 16V8l16 16-16 16v-8H8V16z"></path>
                                    </svg>
                                    <div class="evolution-sprite monster-sprite sprite-2"></div>
                                </div>
                                <div class=evolution-label>
                                    <span>Bulbasaur evolves into Ivysaur <strong>at level 16</strong>.</span></div>
                            </div>
                        </div>
                        <h2 style="background: rgb(76, 140, 44)" class=detail-subheader>Moves</h2>
                        <div class=monster-moves></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>!function e(t, n, i) {
        function o(s, l) {
            if (!n[s]) {
                if (!t[s]) {
                    var a = "function" == typeof require && require;
                    if (!l && a)return a(s, !0);
                    if (r)return r(s, !0);
                    var c = new Error("Cannot find module '" + s + "'");
                    throw c.code = "MODULE_NOT_FOUND", c
                }
                var u = n[s] = {exports: {}};
                t[s][0].call(u.exports, function (e) {
                    var n = t[s][1][e];
                    return o(n ? n : e)
                }, u, u.exports, e, t, n, i)
            }
            return n[s].exports
        }

        for (var r = "function" == typeof require && require, s = 0; s < i.length; s++)o(i[s]);
        return o
    }({
        1: [function (e, t, n) {
            t.exports = {debug: !0}
        }, {}], 2: [function (e, t, n) {
            "use strict";
            function i(e) {
                e._muiDropdown !== !0 && (e._muiDropdown = !0, s.on(e, "click", o))
            }

            function o(e) {
                if (0 === e.button) {
                    var t = this;
                    null === t.getAttribute("disabled") && (e.preventDefault(), e.stopPropagation(), r(t))
                }
            }

            function r(e) {
                function t() {
                    s.removeClass(o, u), s.off(r, "click", t)
                }

                function n() {
                    var n = i.getBoundingClientRect(), l = e.getBoundingClientRect(), a = l.top - n.top + l.height;
                    s.css(o, "top", a + "px"), s.addClass(o, u), s.on(r, "click", t)
                }

                var i = e.parentNode, o = e.nextElementSibling, r = i.ownerDocument;
                return o && s.hasClass(o, d) ? void(s.hasClass(o, u) ? t() : n()) : l.raiseError("Dropdown menu element not found")
            }

            var s = e("./lib/jqLite.js"), l = e("./lib/util.js"), a = "data-mui-toggle", c = '[data-mui-toggle="dropdown"]', u = "mui--is-open", d = "mui-dropdown__menu";
            t.exports = {
                initListeners: function () {
                    for (var e = document, t = e.querySelectorAll(c), n = t.length - 1; n >= 0; n--)i(t[n]);
                    l.onNodeInserted(function (e) {
                        "dropdown" === e.getAttribute(a) && i(e)
                    })
                }
            }
        }, {"./lib/jqLite.js": 5, "./lib/util.js": 6}], 3: [function (e, t, n) {
            "use strict";
            function i(e) {
                e._muiSelect !== !0 && (e._muiSelect = !0, "ontouchstart" in h.documentElement || new o(e))
            }

            function o(e) {
                this.selectEl = e, this.wrapperEl = e.parentNode, this.useDefault = !1, s.on(e, "mousedown", l.callback(this, "mousedownHandler")), s.on(e, "focus", l.callback(this, "focusHandler")), s.on(e, "click", l.callback(this, "clickHandler")), this.wrapperEl.tabIndex = -1;
                var t = l.callback(this, "wrapperFocusHandler");
                s.on(this.wrapperEl, "focus", t)
            }

            function r(e) {
                this.origIndex = null, this.currentIndex = null, this.selectEl = e, this.menuEl = this._createMenuEl(e), this.clickCallbackFn = l.callback(this, "clickHandler"), this.keydownCallbackFn = l.callback(this, "keydownHandler"), this.destroyCallbackFn = l.callback(this, "destroy"), e.parentNode.appendChild(this.menuEl), setTimeout(function () {
                    "body" !== h.activeElement.nodeName.toLowerCase() && h.activeElement.blur()
                }, 0), s.on(this.menuEl, "click", this.clickCallbackFn), s.on(h, "keydown", this.keydownCallbackFn), s.on(p, "resize", this.destroyCallbackFn);
                var t = this.destroyCallbackFn;
                setTimeout(function () {
                    s.on(h, "click", t)
                }, 0)
            }

            var s = e("../lib/jqLite.js"), l = e("../lib/util.js"), a = "mui-select", c = ".mui-select > select", u = "mui-select__menu", d = 42, f = 8, h = document, p = window;
            o.prototype.mousedownHandler = function (e) {
                0 === e.button && this.useDefault !== !0 && e.preventDefault()
            }, o.prototype.focusHandler = function (e) {
                if (this.useDefault !== !0) {
                    var t = this.selectEl, n = this.wrapperEl, i = t.tabIndex, o = l.callback(this, "keydownHandler");
                    s.on(h, "keydown", o), t.tabIndex = -1, s.one(n, "blur", function () {
                        t.tabIndex = i, s.off(h, "keydown", o)
                    }), n.focus()
                }
            }, o.prototype.keydownHandler = function (e) {
                32 !== e.keyCode && 38 !== e.keyCode && 40 !== e.keyCode || (e.preventDefault(), this.selectEl.disabled !== !0 && this.renderMenu())
            }, o.prototype.wrapperFocusHandler = function () {
                if (this.selectEl.disabled)return this.wrapperEl.blur()
            }, o.prototype.clickHandler = function (e) {
                0 === e.button && this.renderMenu()
            }, o.prototype.renderMenu = function () {
                return this.useDefault === !0 ? this.useDefault = !1 : void new r(this.selectEl)
            }, r.prototype._createMenuEl = function (e) {
                var t, n, i, o, r, l, a = h.createElement("div"), c = e.children, p = c.length, m = 0, v = 13;
                for (a.className = u, i = 0; i < p; i++)t = c[i], n = h.createElement("div"), n.textContent = t.textContent, n._muiPos = i, t.selected && (m = i), a.appendChild(n);
                a.children[m].setAttribute("selected", !0), this.origIndex = m, this.currentIndex = m;
                var b = h.documentElement.clientHeight, y = p * d + 2 * f;
                return y = Math.min(y, b), s.css(a, "height", y + "px"), v += m * d, v *= -1, o = -1 * e.getBoundingClientRect().top, r = b - y + o, l = Math.max(v, o), l = Math.min(l, r), s.css(a, "top", l + "px"), a
            }, r.prototype.keydownHandler = function (e) {
                var t = e.keyCode;
                return 9 === t ? this.destroy() : (27 !== t && 40 !== t && 38 !== t && 13 !== t || e.preventDefault(), void(27 === t ? this.destroy() : 40 === t ? this.increment() : 38 === t ? this.decrement() : 13 === t && (this.selectCurrent(), this.destroy())))
            }, r.prototype.clickHandler = function (e) {
                e.stopPropagation();
                var t = e.target._muiPos;
                void 0 !== t && (this.currentIndex = t, this.selectCurrent(), this.destroy())
            }, r.prototype.increment = function () {
                this.currentIndex !== this.menuEl.children.length - 1 && (this.menuEl.children[this.currentIndex].removeAttribute("selected"), this.currentIndex += 1, this.menuEl.children[this.currentIndex].setAttribute("selected", !0))
            }, r.prototype.decrement = function () {
                0 !== this.currentIndex && (this.menuEl.children[this.currentIndex].removeAttribute("selected"), this.currentIndex -= 1, this.menuEl.children[this.currentIndex].setAttribute("selected", !0))
            }, r.prototype.selectCurrent = function () {
                this.currentIndex !== this.origIndex && (this.selectEl.children[this.origIndex].selected = !1, this.selectEl.children[this.currentIndex].selected = !0, l.dispatchEvent(this.selectEl, "change"))
            }, r.prototype.destroy = function () {
                this.menuEl.parentNode.removeChild(this.menuEl), this.selectEl.focus(), s.off(this.menuEl, "click", this.clickCallbackFn), s.off(h, "keydown", this.keydownCallbackFn), s.off(h, "click", this.destroyCallbackFn), s.off(p, "resize", this.destroyCallbackFn)
            }, t.exports = {
                initListeners: function () {
                    for (var e = h.querySelectorAll(c), t = e.length - 1; t >= 0; t--)i(e[t]);
                    l.onNodeInserted(function (e) {
                        "SELECT" === e.tagName && s.hasClass(e.parentNode, a) && i(e)
                    })
                }
            }
        }, {"../lib/jqLite.js": 5, "../lib/util.js": 6}], 4: [function (e, t, n) {
            "use strict";
            function i(e) {
                e._muiTextfield !== !0 && (e._muiTextfield = !0, e.value.length ? r.addClass(e, c) : r.addClass(e, a), r.on(e, "input", o), r.on(e, "focus", function () {
                    r.addClass(this, u)
                }))
            }

            function o() {
                var e = this;
                e.value.length ? (r.removeClass(e, a), r.addClass(e, c)) : (r.removeClass(e, c), r.addClass(e, a)), r.addClass(e, u)
            }

            var r = e("../lib/jqLite.js"), s = e("../lib/util.js"), l = ".mui-textfield > input, .mui-textfield > textarea", a = "mui--is-empty", c = "mui--is-not-empty", u = "mui--is-dirty", d = "mui-textfield--float-label";
            t.exports = {
                initialize: i, initListeners: function () {
                    for (var e = document, t = e.querySelectorAll(l), n = t.length - 1; n >= 0; n--)i(t[n]);
                    s.onNodeInserted(function (e) {
                        "INPUT" !== e.tagName && "TEXTAREA" !== e.tagName || i(e)
                    }), setTimeout(function () {
                        var e = ".mui-textfield.mui-textfield--float-label > label {" + ["-webkit-transition", "-moz-transition", "-o-transition", "transition", ""].join(":all .15s ease-out;") + "}";
                        s.loadStyle(e)
                    }, 150), s.supportsPointerEvents() === !1 && r.on(document, "click", function (e) {
                        var t = e.target;
                        if ("LABEL" === t.tagName && r.hasClass(t.parentNode, d)) {
                            var n = t.previousElementSibling;
                            n && n.focus()
                        }
                    })
                }
            }
        }, {"../lib/jqLite.js": 5, "../lib/util.js": 6}], 5: [function (e, t, n) {
            "use strict";
            function i(e, t) {
                if (t && e.setAttribute) {
                    for (var n, i = h(e), o = t.split(" "), r = 0; r < o.length; r++)n = o[r].trim(), i.indexOf(" " + n + " ") === -1 && (i += n + " ");
                    e.setAttribute("class", i.trim())
                }
            }

            function o(e, t, n) {
                if (void 0 === t)return getComputedStyle(e);
                var i = s(t);
                {
                    if ("object" !== i) {
                        "string" === i && void 0 !== n && (e.style[p(t)] = n);
                        var o = getComputedStyle(e), r = "array" === s(t);
                        if (!r)return m(e, t, o);
                        for (var l, a = {}, c = 0; c < t.length; c++)l = t[c], a[l] = m(e, l, o);
                        return a
                    }
                    for (var l in t)e.style[p(l)] = t[l]
                }
            }

            function r(e, t) {
                return !(!t || !e.getAttribute) && h(e).indexOf(" " + t + " ") > -1
            }

            function s(e) {
                if (void 0 === e)return "undefined";
                var t = Object.prototype.toString.call(e);
                if (0 === t.indexOf("[object "))return t.slice(8, -1).toLowerCase();
                throw new Error("MUI: Could not understand type: " + t)
            }

            function l(e, t, n, i) {
                i = void 0 !== i && i, e.addEventListener(t, n, i);
                var o = e._muiEventCache = e._muiEventCache || {};
                o[t] = o[t] || [], o[t].push([n, i])
            }

            function a(e, t, n, i) {
                i = void 0 !== i && i;
                var o, r, s = e._muiEventCache = e._muiEventCache || {}, l = s[t] || [];
                for (r = l.length; r--;)o = l[r], (void 0 === n || o[0] === n && o[1] === i) && (l.splice(r, 1), e.removeEventListener(t, o[0], o[1]))
            }

            function c(e, t, n, i) {
                l(e, t, function i(o) {
                    n && n.apply(this, arguments), a(e, t, i)
                }, i)
            }

            function u(e) {
                var t, n, i = window, o = document.documentElement, r = e.getBoundingClientRect();
                return t = (i.pageXOffset || o.scrollLeft) - (o.clientLeft || 0), n = (i.pageYOffset || o.scrollTop) - (o.clientTop || 0), {
                    top: r.top + n,
                    left: r.left + t,
                    height: r.height,
                    width: r.width
                }
            }

            function d(e) {
                var t = !1, n = !0, i = document, o = i.defaultView, r = i.documentElement, s = i.addEventListener ? "addEventListener" : "attachEvent", l = i.addEventListener ? "removeEventListener" : "detachEvent", a = i.addEventListener ? "" : "on", c = function (n) {
                    "readystatechange" == n.type && "complete" != i.readyState || (("load" == n.type ? o : i)[l](a + n.type, c, !1), !t && (t = !0) && e.call(o, n.type || n))
                }, u = function () {
                    try {
                        r.doScroll("left")
                    } catch (e) {
                        return void setTimeout(u, 50)
                    }
                    c("poll")
                };
                if ("complete" == i.readyState)e.call(o, "lazy"); else {
                    if (i.createEventObject && r.doScroll) {
                        try {
                            n = !o.frameElement
                        } catch (e) {
                        }
                        n && u()
                    }
                    i[s](a + "DOMContentLoaded", c, !1), i[s](a + "readystatechange", c, !1), o[s](a + "load", c, !1)
                }
            }

            function f(e, t) {
                if (t && e.setAttribute) {
                    for (var n, i = h(e), o = t.split(" "), r = 0; r < o.length; r++)for (n = o[r].trim(); i.indexOf(" " + n + " ") >= 0;)i = i.replace(" " + n + " ", " ");
                    e.setAttribute("class", i.trim())
                }
            }

            function h(e) {
                var t = (e.getAttribute("class") || "").replace(/[\n\t]/g, "");
                return " " + t + " "
            }

            function p(e) {
                return e.replace(b, function (e, t, n, i) {
                    return i ? n.toUpperCase() : n
                }).replace(y, "Moz$1")
            }

            function m(e, t, n) {
                var i;
                return i = n.getPropertyValue(t), "" !== i || e.ownerDocument || (i = e.style[p(t)]), i
            }

            var v, b = /([\:\-\_]+(.))/g, y = /^moz([A-Z])/;
            v = {
                multiple: !0,
                selected: !0,
                checked: !0,
                disabled: !0,
                readonly: !0,
                required: !0,
                open: !0
            }, t.exports = {
                addClass: i,
                css: o,
                hasClass: r,
                off: a,
                offset: u,
                on: l,
                one: c,
                ready: d,
                removeClass: f,
                type: s
            }
        }, {}], 6: [function (e, t, n) {
            "use strict";
            function i() {
                if (p.debug && "undefined" != typeof v.console)try {
                    v.console.log.apply(v.console, arguments)
                } catch (t) {
                    var e = Array.prototype.slice.call(arguments);
                    v.console.log(e.join("\n"))
                }
            }

            function o(e) {
                if (b.createStyleSheet)b.createStyleSheet().cssText = e; else {
                    var t = b.createElement("style");
                    t.type = "text/css", t.styleSheet ? t.styleSheet.cssText = e : t.appendChild(b.createTextNode(e)), f.insertBefore(t, f.firstChild)
                }
            }

            function r(e) {
                throw new Error("MUI: " + e)
            }

            function s(e) {
                y.push(e), void 0 === y._initialized && (m.on(b, "animationstart", l), m.on(b, "mozAnimationStart", l), m.on(b, "webkitAnimationStart", l), y._initialized = !0)
            }

            function l(e) {
                if ("mui-node-inserted" === e.animationName)for (var t = e.target, n = y.length - 1; n >= 0; n--)y[n](t)
            }

            function a(e) {
                var t = "";
                for (var n in e)t += e[n] ? n + " " : "";
                return t.trim()
            }

            function c() {
                if (void 0 !== h)return h;
                var e = document.createElement("x");
                return e.style.cssText = "pointer-events:auto", h = "auto" === e.style.pointerEvents
            }

            function u(e, t) {
                return function () {
                    e[t].apply(e, arguments)
                }
            }

            function d(e, t, n, i, o) {
                var r, s = document.createEvent("HTMLEvents"), n = void 0 === n || n, i = void 0 === i || i;
                if (s.initEvent(t, n, i), o)for (r in o)s[r] = o[r];
                return e && e.dispatchEvent(s), s
            }

            var f, h, p = e("../config.js"), m = e("./jqLite.js"), v = window, b = window.document, y = [];
            f = b.head || b.getElementsByTagName("head")[0] || b.documentElement, t.exports = {
                callback: u,
                classNames: a,
                dispatchEvent: d,
                log: i,
                loadStyle: o,
                onNodeInserted: s,
                raiseError: r,
                supportsPointerEvents: c
            }
        }, {"../config.js": 1, "./jqLite.js": 5}], 7: [function (e, t, n) {
            !function (t) {
                "use strict";
                if (!t._muiLoadedJS) {
                    t._muiLoadedJS = !0;
                    var n = e("./lib/jqLite.js"), i = (e("./lib/util.js"), e("./forms/textfield.js")), o = e("./forms/select.js"), r = e("./ripple.js"), s = e("./dropdowns.js"), l = e("./tabs.js"), a = e("./overlay.js");
                    t.mui = {overlay: a, tabs: l.api}, n.ready(function () {
                        i.initListeners(), o.initListeners(), r.initListeners(), s.initListeners(), l.initListeners()
                    })
                }
            }(window)
        }, {
            "./dropdowns.js": 2,
            "./forms/select.js": 3,
            "./forms/textfield.js": 4,
            "./lib/jqLite.js": 5,
            "./lib/util.js": 6,
            "./overlay.js": 8,
            "./ripple.js": 9,
            "./tabs.js": 10
        }], 8: [function (e, t, n) {
            "use strict";
            function i(e) {
                var t;
                if ("on" === e) {
                    for (var n, i, s, l = arguments.length - 1; l > 0; l--)n = arguments[l], "object" === h.type(n) && (i = n), n instanceof Element && 1 === n.nodeType && (s = n);
                    i = i || {}, void 0 === i.keyboard && (i.keyboard = !0), void 0 === i.static && (i.static = !1), t = o(i, s)
                } else"off" === e ? t = r() : f.raiseError("Expecting 'on' or 'off'");
                return t
            }

            function o(e, t) {
                var n = document.body, i = document.getElementById(p);
                if (h.addClass(n, m), i) {
                    for (; i.firstChild;)i.removeChild(i.firstChild);
                    t && i.appendChild(t)
                } else i = document.createElement("div"), i.setAttribute("id", p), t && i.appendChild(t), n.appendChild(i);
                return v.test(navigator.userAgent) && h.css(i, "cursor", "pointer"), e.keyboard ? s() : l(), e.static ? u(i) : c(i), i.muiOptions = e, i
            }

            function r() {
                var e, t = document.getElementById(p);
                if (t) {
                    for (; t.firstChild;)t.removeChild(t.firstChild);
                    t.parentNode.removeChild(t), e = t.muiOptions.onclose
                }
                return h.removeClass(document.body, m), l(), u(t), e && e(), t
            }

            function s() {
                h.on(document, "keyup", a)
            }

            function l() {
                h.off(document, "keyup", a)
            }

            function a(e) {
                27 === e.keyCode && r()
            }

            function c(e) {
                h.on(e, "click", d)
            }

            function u(e) {
                h.off(e, "click", d)
            }

            function d(e) {
                e.target.id === p && r()
            }

            var f = e("./lib/util.js"), h = e("./lib/jqLite.js"), p = "mui-overlay", m = "mui--overflow-hidden", v = /(iPad|iPhone|iPod)/g;
            t.exports = i
        }, {"./lib/jqLite.js": 5, "./lib/util.js": 6}], 9: [function (e, t, n) {
            "use strict";
            function i(e) {
                e._muiRipple !== !0 && (e._muiRipple = !0, "INPUT" !== e.tagName && (r.on(e, "touchstart", o), r.on(e, "mousedown", o)))
            }

            function o(e) {
                if (0 === e.button) {
                    var t = this;
                    if (t.disabled !== !0 && t.touchFlag !== !0) {
                        t.touchFlag = !0, setTimeout(function () {
                            t.touchFlag = !1
                        }, 100);
                        var n = document.createElement("div");
                        n.className = c;
                        var i, o, s = r.offset(t), l = e.pageX - s.left, u = e.pageY - s.top;
                        i = r.hasClass(t, a) ? s.height / 2 : s.height, o = i / 2, r.css(n, {
                            height: i + "px",
                            width: i + "px",
                            top: u - o + "px",
                            left: l - o + "px"
                        }), t.appendChild(n), window.setTimeout(function () {
                            t.removeChild(n)
                        }, 2e3)
                    }
                }
            }

            var r = e("./lib/jqLite.js"), s = e("./lib/util.js"), l = "mui-btn", a = "mui-btn--fab", c = "mui-ripple-effect";
            t.exports = {
                initListeners: function () {
                    for (var e = document, t = e.getElementsByClassName(l), n = t.length - 1; n >= 0; n--)i(t[n]);
                    s.onNodeInserted(function (e) {
                        r.hasClass(e, l) && i(e)
                    })
                }
            }
        }, {"./lib/jqLite.js": 5, "./lib/util.js": 6}], 10: [function (e, t, n) {
            "use strict";
            function i(e) {
                e._muiTabs !== !0 && (e._muiTabs = !0, l.on(e, "click", o))
            }

            function o(e) {
                if (0 === e.button) {
                    var t = this;
                    null === t.getAttribute("disabled") && r(t)
                }
            }

            function r(e) {
                var t, n, i, o, r, c, u, b, y, g = e.parentNode, E = e.getAttribute(d), C = document.getElementById(E);
                l.hasClass(g, f) || (C || a.raiseError('Tab pane "' + E + '" not found'), n = s(C), i = n.id, y = "[" + d + '="' + i + '"]', o = document.querySelectorAll(y)[0], t = o.parentNode, r = {
                    paneId: E,
                    relatedPaneId: i
                }, c = {
                    paneId: i,
                    relatedPaneId: E
                }, u = a.dispatchEvent(o, m, !0, !0, c), b = a.dispatchEvent(e, h, !0, !0, r), setTimeout(function () {
                    u.defaultPrevented || b.defaultPrevented || (t && l.removeClass(t, f), n && l.removeClass(n, f), l.addClass(g, f), l.addClass(C, f), a.dispatchEvent(o, v, !0, !1, c), a.dispatchEvent(e, p, !0, !1, r))
                }, 0))
            }

            function s(e) {
                for (var t, n = e.parentNode.children, i = n.length, o = null; i-- && !o;)t = n[i], t !== e && l.hasClass(t, f) && (o = t);
                return o
            }

            var l = e("./lib/jqLite.js"), a = e("./lib/util.js"), c = "data-mui-toggle", u = "[" + c + '="tab"]', d = "data-mui-controls", f = "mui--is-active", h = "mui.tabs.showstart", p = "mui.tabs.showend", m = "mui.tabs.hidestart", v = "mui.tabs.hideend";
            t.exports = {
                initListeners: function () {
                    for (var e = document.querySelectorAll(u), t = e.length - 1; t >= 0; t--)i(e[t]);
                    a.onNodeInserted(function (e) {
                        "tab" === e.getAttribute(c) && i(e)
                    })
                }, api: {
                    activate: function (e) {
                        var t = "[" + d + "=" + e + "]", n = document.querySelectorAll(t);
                        n.length || a.raiseError('Tab control for pane "' + e + '" not found'), r(n[0])
                    }
                }
            }
        }, {"./lib/jqLite.js": 5, "./lib/util.js": 6}]
    }, {}, [7]);</script>
<script>require = function e(r, n, t) {
        function o(s, a) {
            if (!n[s]) {
                if (!r[s]) {
                    var u = "function" == typeof require && require;
                    if (!a && u)return u(s, !0);
                    if (i)return i(s, !0);
                    var f = new Error("Cannot find module '" + s + "'");
                    throw f.code = "MODULE_NOT_FOUND", f
                }
                var c = n[s] = {exports: {}};
                r[s][0].call(c.exports, function (e) {
                    var n = r[s][1][e];
                    return o(n ? n : e)
                }, c, c.exports, e, r, n, t)
            }
            return n[s].exports
        }

        for (var i = "function" == typeof require && require, s = 0; s < t.length; s++)o(t[s]);
        return o
    }({
        91: [function (e, r, n) {
            "use strict";
            var t, o = e(42), i = e(90);
            t = i.hasWebWorkerIDB() ? new Worker("resources/views/worker.js") : new o("resources/views/worker.js"), t.addEventListener("error", function (e) {
            }), t.postMessage({origin: window.location.origin, type: "origin"}), r.exports = t
        }, {42: 42, 90: 90}], 90: [function (e, r, n) {
            "use strict";
            r.exports = {
                hasWebWorkerIDB: function () {
                    var e = "undefined" != typeof openDatabase && /(Safari|iPhone|iPad|iPod)/.test(navigator.userAgent) && !/Chrome/.test(navigator.userAgent) && !/BlackBerry/.test(navigator.platform);
                    return !e
                }, canRunHighPerfAnims: function () {
                    var e = window.location.search.match(/highPerfAnims=(\d)/);
                    if (e)return 1 == e[1];
                    var r = navigator.userAgent.match(/Android 4\./), n = navigator.userAgent.match(/Firefox/), t = navigator.userAgent.match(/(?:MSIE|Edge)/);
                    return !r && !n && !t
                }
            }
        }, {}], 95: [function (e, r, n) {
            "use strict";
            var t = !1, o = document.createElement("canvas");
            o.getContext && o.getContext("2d") && (t = /^data:image\/webp/.test(o.toDataURL("image/webp"))), r.exports = function () {
                return t
            }
        }, {}], 42: [function (require, module, exports) {
            (function (global) {
                "use strict";
                function doEval(self, __pseudoworker_script) {
                    (function () {
                        eval(__pseudoworker_script)
                    }).call(global)
                }

                function PseudoWorker(e) {
                    function r(e, r) {
                        for (var n = -1; ++n < e.length;)e[n] && r(e[n])
                    }

                    function n(e) {
                        return function (r) {
                            r({type: "error", error: e, message: e.message})
                        }
                    }

                    function t(e, r) {
                        "message" === e ? g.push(r) : "error" === e && l.push(r)
                    }

                    function o(e, r) {
                        var n;
                        if ("message" === e)n = g; else {
                            if ("error" !== e)return;
                            n = l
                        }
                        for (var t = -1; ++t < n.length;) {
                            var o = n[t];
                            if (o === r) {
                                delete n[t];
                                break
                            }
                        }
                    }

                    function i(e) {
                        var t = n(e);
                        "function" == typeof y.onerror && t(y.onerror), p && "function" == typeof p.onerror && t(p.onerror), r(l, t), r(h, t)
                    }

                    function s(e) {
                        function n(r) {
                            try {
                                r({data: e})
                            } catch (e) {
                                i(e)
                            }
                        }

                        p && "function" == typeof p.onmessage && n(p.onmessage), r(v, n)
                    }

                    function a(e) {
                        if ("undefined" == typeof e)throw new Error("postMessage() requires an argument");
                        if (!w)return d ? void s(e) : void m.push(e)
                    }

                    function u() {
                        w = !0
                    }

                    function f(e) {
                        function n(r) {
                            r({data: e})
                        }

                        "function" == typeof y.onmessage && n(y.onmessage), r(g, n)
                    }

                    function c(e, r) {
                        "message" === e ? v.push(r) : "error" === e && h.push(r)
                    }

                    var d, p, g = [], l = [], v = [], h = [], m = [], w = !1, y = this, E = new XMLHttpRequest;
                    return E.open("GET", e), E.onreadystatechange = function () {
                        if (4 === E.readyState)if (E.status >= 200 && E.status < 400)for (d = E.responseText, p = {
                            postMessage: f,
                            addEventListener: c
                        }, doEval(p, d); m.length;)s(m.pop()); else i(new Error("cannot find script " + e))
                    }, E.send(), y.postMessage = a, y.addEventListener = t, y.removeEventListener = o, y.terminate = u, y
                }

                module.exports = PseudoWorker
            }).call(this, "undefined" != typeof global ? global : "undefined" != typeof self ? self : "undefined" != typeof window ? window : {})
        }, {}]
    }, {}, []);</script>
<script>require = function e(r, n, t) {
        function i(u, s) {
            if (!n[u]) {
                if (!r[u]) {
                    var f = "function" == typeof require && require;
                    if (!s && f)return f(u, !0);
                    if (o)return o(u, !0);
                    var c = new Error("Cannot find module '" + u + "'");
                    throw c.code = "MODULE_NOT_FOUND", c
                }
                var a = n[u] = {exports: {}};
                r[u][0].call(a.exports, function (e) {
                    var n = r[u][1][e];
                    return i(n ? n : e)
                }, a, a.exports, e, r, n, t)
            }
            return n[u].exports
        }

        for (var o = "function" == typeof require && require, u = 0; u < t.length; u++)i(t[u]);
        return i
    }({
        74: [function (e, r, n) {
            "use strict";
            e(73), e(91)
        }, {73: 73, 91: 91}], 73: [function (e, r, n) {
            "use strict";
            function t(e) {
                var r = document.createElement("link");
                return r.rel = "stylesheet", r.href = e, r.media = "only foo", document.body.appendChild(r), setTimeout(function () {
                    r.media = "all"
                }), r
            }

            function i(e, r) {
                function n() {
                    if (!(i > e)) {
                        var o = r(i), u = t(o);
                        i++, u.onload = n
                    }
                }

                var i = 1;
                n()
            }

            var o = e(95), u = e(93), s = u.numSpriteCssFiles, f = o();
            i(s, function (e) {
                return "css/sprites" + (f ? "-webp" : "") + "-" + e + ".css"
            })
        }, {93: 93, 95: 95}], 93: [function (e, r, n) {
            "use strict";
            r.exports = {pageSize: 3e6, numSpriteCssFiles: 10, initialWindowSize: 100}
        }, {}]
    }, {}, [74]);</script>
<script src=https://www.pokedex.org/js/main.js></script>
</body>
</html>