import os
import re

def replace_in_file(filepath):
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    
    # Pattern to match $this->data['page_js'] = ob_get_clean();
    # Supporting both single and double quotes
    pattern = r"\$this->data\s*\[\s*['\"]page_js['\"]\s*\]\s*=\s*ob_get_clean\(\)\s*;"
    replacement = "$this->load->vars(['page_js' => ob_get_clean()]);"
    
    new_content = re.sub(pattern, replacement, content)
    
    if content != new_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        return True
    return False

def main():
    root_dir = r"c:\xampphp7\htdocs\op_kcn_new\application\views"
    count = 0
    for root, dirs, files in os.walk(root_dir):
        for file in files:
            if file.endswith(".php"):
                path = os.path.join(root, file)
                if replace_in_file(path):
                    print(f"Updated: {path}")
                    count += 1
    print(f"Total files updated: {count}")

if __name__ == "__main__":
    main()
