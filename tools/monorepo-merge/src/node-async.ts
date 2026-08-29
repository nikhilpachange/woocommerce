import { access as fsAccess, PathLike } from 'fs';
import { exec as cpExec, ExecOptions } from 'child_process';
import { promisify } from 'util';

export const access = (path: PathLike, mode?: number): Promise<void> =>
	promisify(fsAccess)(path, mode);

export const exec = (command: string, options?: ExecOptions): Promise<string | Buffer> =>
	new Promise((resolve, reject) =>
		cpExec(command, options, (err, stdout, stderr) => (err ? reject(stderr) : resolve(stdout)))
	);
