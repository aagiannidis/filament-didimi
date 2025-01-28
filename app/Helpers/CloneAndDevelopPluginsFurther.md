You're absolutely correct that modifying code in the `vendor` folder is not a good practice because changes in the vendor directory are not tracked by your project's Git repository and can be overwritten when you update Composer dependencies.

The best approach is to **fork the plugin** and include your fork in your project. Here's a step-by-step process:

---

### **1. Fork the Plugin**
1. Go to the GitHub (or other VCS) repository of the plugin you’re using.
2. Click the **Fork** button to create a copy of the repository in your own GitHub account.
3. Clone your forked repository locally:
   ```bash
   git clone https://github.com/your-username/plugin-name
   ```

---

### **2. Add the Fork to Your Project**
You need to make Composer point to your fork instead of the original package.

#### **Method 1: Use a Local Path**
1. Move the plugin into your project directory, e.g., `packages/plugin-name`.
2. Add the plugin to your `composer.json` file:
   ```json
   "repositories": [
       {
           "type": "path",
           "url": "./packages/plugin-name"
       }
   ]
   ```
3. Require the plugin with Composer:
   ```bash
   composer require vendor-name/plugin-name:dev-main
   ```

#### **Method 2: Use Your GitHub Fork**
1. Update your `composer.json` to point to your fork:
   ```json
   "repositories": [
       {
           "type": "vcs",
           "url": "https://github.com/your-username/plugin-name"
       }
   ]
   ```
2. Require the plugin using your fork:
   ```bash
   composer require vendor-name/plugin-name:dev-main
   ```

3. Commit these changes so that others working on the project will also pull your forked version.

---

### **3. Modify the Plugin**
Now that you’re using your fork, you can freely modify the plugin source code. Do this in the following ways:

1. If you used **Method 1 (Local Path)**:
   - Directly modify the code in the `packages/plugin-name` directory.
   - This directory will be part of your project and tracked by Git.

2. If you used **Method 2 (GitHub Fork)**:
   - Modify the code in your local forked repository.
   - Push changes back to your fork on GitHub:
     ```bash
     git add .
     git commit -m "Enhance functionality"
     git push origin main
     ```

---

### **4. Keep the Plugin Updated**
If the original plugin gets updated and you want to include those updates in your fork:
1. Add the original plugin as a remote in your fork:
   ```bash
   git remote add upstream https://github.com/original-vendor/plugin-name
   ```
2. Fetch and merge the changes:
   ```bash
   git fetch upstream
   git merge upstream/main
   ```
3. Resolve any conflicts, test, and push the updated code to your fork:
   ```bash
   git push origin main
   ```

---

### **5. Benefits of This Approach**
- **Trackability**: The plugin is now tracked in your Git repository, so you can collaborate on it.
- **Customizability**: You can extend the functionality without worrying about losing changes during Composer updates.
- **Maintainability**: You can easily pull upstream changes from the original plugin and merge them into your fork.

---

### **6. Alternative: Use a Custom Package**
If the plugin is relatively small and you don’t need to track updates from the original source, you can create your own package:

1. Move the plugin code into your project (e.g., `app/CustomPlugins/PluginName`).
2. Adjust the namespace in the plugin’s files to match your project’s structure.
3. Autoload it via Composer in your `composer.json` file:
   ```json
   "autoload": {
       "psr-4": {
           "App\\CustomPlugins\\PluginName\\": "app/CustomPlugins/PluginName/src"
       }
   }
   ```
4. Run Composer's autoload dump to register the new namespace:
   ```bash
   composer dump-autoload
   ```

---

### Which Option to Choose?

- **Fork the Plugin**: If you plan to maintain compatibility with the original plugin and potentially merge updates.
- **Custom Package**: If the plugin is small, and you don't expect to update it from the original source.

---

Let me know if you need help setting up or maintaining your fork! 😊
