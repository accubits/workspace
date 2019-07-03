import { TestBed, inject } from '@angular/core/testing';

import { ActivityApiService } from './activity-api.service';

describe('ActivityApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [ActivityApiService]
    });
  });

  it('should be created', inject([ActivityApiService], (service: ActivityApiService) => {
    expect(service).toBeTruthy();
  }));
});
