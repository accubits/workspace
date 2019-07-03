import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PerformanceNavbarComponent } from './performance-navbar.component';

describe('PerformanceNavbarComponent', () => {
  let component: PerformanceNavbarComponent;
  let fixture: ComponentFixture<PerformanceNavbarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PerformanceNavbarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PerformanceNavbarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
