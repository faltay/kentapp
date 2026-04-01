# Introduction

Public menu API for QR Menu SaaS. Fetch restaurant menus, categories and items to build your own integrations.

<aside>
    <strong>Base URL</strong>: <code>http://qrmenu.test</code>
</aside>

Welcome to the **QR Menu API**. This API lets you programmatically access restaurant menus, categories and menu items.

**Base URL:** `/api/v1`

**Rate limit:** 60 requests / minute per IP.

**Locale:** Pass `?locale=tr` or `Accept-Language: tr` header to get localised content (default: `en`).

<aside>All endpoints return JSON. Inactive or suspended restaurants return <code>404</code>.</aside>

