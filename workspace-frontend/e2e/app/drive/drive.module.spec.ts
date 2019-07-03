import { DriveModule } from './drive.module';

describe('DriveModule', () => {
  let driveModule: DriveModule;

  beforeEach(() => {
    driveModule = new DriveModule();
  });

  it('should create an instance', () => {
    expect(driveModule).toBeTruthy();
  });
});
