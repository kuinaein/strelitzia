const express = require('express');
const router = express.Router();

const { spawn, spawnSync } = require('child_process');
const rimraf = require('rimraf');

router.get('/run-tests/ie', (req, res, next) => {
  spawn('C:\\Program Files\\Internet Explorer\\iexplore.exe', [req.query.url]);
  res.send('OK');
});

router.get('/run-tests/edge', (req, res, next) => {
  spawn('cmd', ['/c', 'start', req.query.url]);
  res.send('OK');
});

router.get('/cleanup', (req, res, next) => {
  spawnSync('taskkill', ['/im', 'iexplore.exe', '/f']);
  spawnSync('cscript', ['//b', 'C:\\vagrant\\kill-edge.wsf']);
  rimraf.sync('C:\\Users\\IEUser\\AppData\\Local\\Microsoft\\Internet Explorer\\Recovery\\*');
  res.send('OK');
});

module.exports = router;
