import os
import re

def fix_model_file(filepath):
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
    
    # Fix search value check
    new_content = content.replace("if($_POST['search']['value'])", "if(isset($_POST['search']['value']) && $_POST['search']['value'])")
    
    # Fix pagination check (if exists)
    pattern_limit = r"if\(\$_POST\['length'\]\s*!=\s*-1\)\s*\$this->db->limit\(\$_POST\['length'\],\s*\$_POST\['start'\]\);"
    replacement_limit = "if(isset($_POST['length']) && $_POST['length'] != -1) $this->db->limit($_POST['length'], isset($_POST['start']) ? $_POST['start'] : 0);"
    new_content = re.sub(pattern_limit, replacement_limit, new_content)
    
    if content != new_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        return True
    return False

def main():
    root_dir = r"c:\xampphp7\htdocs\op_kcn_new\application\models"
    count = 0
    for file in os.listdir(root_dir):
        if file.endswith("_model.php"):
            path = os.path.join(root_dir, file)
            if fix_model_file(path):
                print(f"Fixed: {file}")
                count += 1
    print(f"Total models fixed: {count}")

if __name__ == "__main__":
    main()
