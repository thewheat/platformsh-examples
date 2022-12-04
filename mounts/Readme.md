# Mounts

https://docs.platform.sh/create-apps/app-reference.html#mounts

- This is a basic PHP project with out any composer that demonstrates mounts
- Mounts share a common parent directory in `/mnt`
- A mount on a blank path will be able to access all files in all mounts


## Example setup
```
mounts:
    'web/path/in/app':
        source: local
        source_path: 'path/in/filesystem'
    'web/things':
        source: local
        source_path: 'my_files'
    'web/path/all':
        source: local
        source_path: ''    
...
web:
    locations:
        "/":
            # The public directory of the app, relative to its root.
            root: "web"
...
```

- Based on mount `'web/path/in/app':`
   - For code file in `/app/web`
   - Writing file to `path/in/app/app_file.txt`
   - Creates file `/mnt/path/in/filesystem/app_file.txt`
   - Accessible via `/app/web/path/in/app/app_file.txt`
   - Publicly accessible via `https://URL/path/in/app/app_file.txt`
- From file created above, based on mount `'web/path/all':`
   - Accessible via `/app/web/path/all/path/in/filesystem/app_file.txt`
   - Publicly acccessible via `https://URL/path/all/path/in/filesystem/app_file.txt`


```
web@app.0:~$ tree /mnt
/mnt
├── app_file.txt
├── my_files
│   ├── app_file.txt
│   └── source_file.txt
├── path
│   └── in
│       └── filesystem
│           ├── app_file.txt
│           └── source_file.txt
├── source_file.txt
```

```
web@app.0:~$ tree /app/web
/app/web
├── index.php
├── path
│   ├── all
│   │   ├── app_file.txt
│   │   ├── my_files
│   │   │   ├── app_file.txt
│   │   │   └── source_file.txt
│   │   ├── path
│   │   │   └── in
│   │   │       └── filesystem
│   │   │           ├── app_file.txt
│   │   │           └── source_file.txt
│   │   ├── source_file.txt
│   └── in
│       └── app
│           ├── app_file.txt
│           └── source_file.txt
└── things
    ├── app_file.txt
    └── source_file.txt
```