# Zoo Repairs — Starlink Landing Page Deployment Package

**Client:** Zoo Repairs (Robert)  
**Target URL:** `https://www.zoorepairs.com.au/computer-services/starlink-installation-brisbane-southside/`  
**WordPress Page ID:** `76959`  
**Form Service:** SplitForms (`https://splitforms.com/api/submit`)  
**Public Form Access Key:** `1d66ead806e3439ea5d8caeab9092c18`  
**Fallback Support Phone:** `0477 319 160`  

---

## 1. Package File Manifest

This deployment directory contains the complete, verified production artifacts:

| Local File | Purpose | Size | Server Destination Path |
| :--- | :--- | :--- | :--- |
| `starlink-installation-brisbane-southside.html` | Self-contained compiled Astro landing page (all CSS inlined, zero local `/assets/` dependencies) | ~144.7 KB | `wp-content/uploads/zoo-starlink/starlink-installation-brisbane-southside.html` |
| `zoo-starlink-standalone.php` | Isolated Must-Use (MU) plugin interceptor for Page ID `76959` with buffer safety & fallback | ~2.5 KB | `wp-content/mu-plugins/zoo-starlink-standalone.php` |
| `DEPLOYMENT_README.md` | Deployment, rollback, cache purge, and verification instructions | Documentation | Project repository only |

---

## 2. Pre-Deployment Configuration Check

### SplitForms Allowed Domains Check
1. Log in to the [SplitForms Dashboard](https://splitforms.com).
2. Open the **Zoo Repairs - Starlink Installation** form.
3. Check **Settings → Security → Allowed Domains**.
4. If domain restrictions are enabled, confirm that `https://www.zoorepairs.com.au` and `zoorepairs.com.au` are added to the whitelist. (If domain whitelisting is disabled, all domains are allowed by default).

---

## 3. Step-by-Step Server Upload Instructions

You can perform this deployment using **WordPress File Manager**, **cPanel File Manager**, or **SFTP**.

> [!IMPORTANT]
> **Always upload the HTML file first, then the MU-plugin second.** This ensures that when the MU-plugin is activated, the target HTML file is already in place.

### Step 1: Upload the Standalone HTML File
1. Open **File Manager** in cPanel or WordPress admin.
2. Navigate to your WordPress root: `wp-content/uploads/`.
3. Check if the directory `zoo-starlink` exists:
   - If not, create a new folder named `zoo-starlink`.
4. Enter `wp-content/uploads/zoo-starlink/`.
5. Upload `starlink-installation-brisbane-southside.html`.
6. Ensure file permissions are set to **`644`** and the folder is **`755`**.

### Step 2: Upload the MU-Plugin Interceptor
1. Navigate to `wp-content/mu-plugins/`.
   - *Note:* If the `mu-plugins` folder does not exist, create it inside `wp-content/`.
2. Inspect the existing files:
   - Notice `zoo-cctv-suburb-pages.php` (or similar CCTV files). **DO NOT MODIFY OR DELETE ANY EXISTING FILES.**
3. Upload `zoo-starlink-standalone.php` directly into `wp-content/mu-plugins/`.
4. Ensure file permissions are set to **`644`**.

---

## 4. Cache Purging Protocol

Because WordPress caching plugins and Cloudflare intercept web requests before PHP executes, you **must** clear the cache to ensure visitors see the new page immediately.

### 1. Purge WordPress Page Cache
- **LiteSpeed Cache:** Click **LiteSpeed Cache** icon in admin top bar → **Purge All - LSCache** (or navigate to *LiteSpeed Cache → Toolbox → Purge* and purge URL `https://www.zoorepairs.com.au/computer-services/starlink-installation-brisbane-southside/`).
- **WP Rocket / WP Super Cache (if installed):** Click *Clear Cache* or purge Page ID `76959`.

### 2. Purge Cloudflare Edge Cache
1. Log in to the [Cloudflare Dashboard](https://dash.cloudflare.com).
2. Select the `zoorepairs.com.au` domain.
3. Go to **Caching → Configuration**.
4. Under **Purge Cache**, choose **Custom Purge**.
5. Select **URL** and enter:
   ```
   https://www.zoorepairs.com.au/computer-services/starlink-installation-brisbane-southside/
   ```
6. Click **Purge**.

---

## 5. Post-Deployment Verification Checklist

Test in an **Incognito / Private Window**:

- [ ] **HTTP Status:** Navigate to `https://www.zoorepairs.com.au/computer-services/starlink-installation-brisbane-southside/`. Verify HTTP 200 OK.
- [ ] **Visual Layout:** Confirm the modern dark Astro layout loads cleanly with zero theme bleed (no Kadence header, menu, or footer wrapper).
- [ ] **Images & Logo:** Verify the Zoo Repairs logo displays at the top header from `https://www.zoorepairs.com.au/wp-content/uploads/2023/03/zoologo.png`.
- [ ] **Interactive Elements:**
  - Test the mobile navigation drawer toggle.
  - Test the FAQ accordion expansion and collapse.
  - Test smooth scrolling to quote form anchors (`#quote-form`).
- [ ] **Form Validation:**
  - Attempt to submit an empty quote form. Confirm required field validation alerts appear.
- [ ] **Live Form Lead Verification (With Client Approval):**
  - Submit one test lead with identifying test text (e.g., `Test Submission - Antigravity Deployment Check`).
  - Confirm the UI displays the green success card.
  - Check the SplitForms dashboard to confirm the lead was recorded.
  - Check the recipient inbox to confirm email notification delivery.
- [ ] **Regression Check:**
  - Visit a CCTV suburb page (e.g., `/cctv-installation-.../`) and the home page to confirm all other WordPress pages are completely unaffected.

---

## 6. Instant Zero-Downtime Rollback Procedure

If you ever need to revert back to the original WordPress Kadence page:

### Option A: Disable the MU-Plugin (Recommended — 5 seconds)
1. Go to `wp-content/mu-plugins/` in File Manager.
2. Rename `zoo-starlink-standalone.php` to `zoo-starlink-standalone.php.disabled` (or delete it).
3. Purge LiteSpeed and Cloudflare caches.
4. The page instantly reverts to the original WordPress Gutenberg/Kadence layout.

### Option B: Automatic Safe Fallback
1. Delete or rename `wp-content/uploads/zoo-starlink/starlink-installation-brisbane-southside.html`.
2. The built-in safety check in `zoo-starlink-standalone.php` automatically detects the missing file and seamlessly passes request handling back to Kadence with zero errors.
