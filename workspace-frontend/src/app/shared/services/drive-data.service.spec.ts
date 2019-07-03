import { TestBed, inject } from '@angular/core/testing';
import { DriveDataService } from './drive-data.service';

describe('DriveDataService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [DriveDataService]
    });
  });

  it('should be created', inject([DriveDataService], (service: DriveDataService) => {
    expect(service).toBeTruthy();
  }));
});
