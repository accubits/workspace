import { TestBed, inject } from '@angular/core/testing';
import { DriveApiService } from './drive-api.service';

describe('DriveApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [DriveApiService]
    });
  });

  it('should be created', inject([DriveApiService], (service: DriveApiService) => {
    expect(service).toBeTruthy();
  }));
});
