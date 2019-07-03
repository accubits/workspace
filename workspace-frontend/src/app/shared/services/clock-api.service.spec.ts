import { TestBed, inject } from '@angular/core/testing';

import { ClockApiService } from './clock-api.service';

describe('ClockApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [ClockApiService]
    });
  });

  it('should be created', inject([ClockApiService], (service: ClockApiService) => {
    expect(service).toBeTruthy();
  }));
});
