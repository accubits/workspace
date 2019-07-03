import { TestBed, inject } from '@angular/core/testing';

import { CalenderApiService } from './calender-api.service';

describe('CalenderApiService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [CalenderApiService]
    });
  });

  it('should be created', inject([CalenderApiService], (service: CalenderApiService) => {
    expect(service).toBeTruthy();
  }));
});
