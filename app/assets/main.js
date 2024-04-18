import * as THREE from './three.module.js';
import { STLLoader } from './STLLoader.js';
import { OrbitControls } from './OrbitControls.js';

var canvases = document.querySelectorAll('.canvas');

var loader = new STLLoader();

console.log(canvases);

canvases.forEach((canvas) => {
  
    var scene = new THREE.Scene();
    var camera = new THREE.PerspectiveCamera(75, canvas.clientWidth / canvas.clientHeight, 0.1, 1000);
    camera.position.z = 5;
    var renderer = new THREE.WebGLRenderer(scene, camera);

    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    canvas.appendChild(renderer.domElement);

    var controls = new OrbitControls(camera, renderer.domElement);

    var fileContent = canvas.getAttribute('data-file');

    loader.load('data:application/octet-stream;base64,' + fileContent, function (geometry) {
        var material = new THREE.MeshNormalMaterial();
        var mesh = new THREE.Mesh(geometry, material);
        console.log(mesh);
        scene.add(mesh);

        var boundingBox = new THREE.Box3().setFromObject(mesh);
    
        var size = boundingBox.getSize(new THREE.Vector3()).length();
    
        var distance = size * 0.5; 
        camera.position.set(distance, distance, distance);

        camera.lookAt(mesh.position);
    
        controls.update();
    });

    function animate() {
        requestAnimationFrame(animate); 
        controls.update();
        renderer.render(scene, camera);
    }

    animate();
});