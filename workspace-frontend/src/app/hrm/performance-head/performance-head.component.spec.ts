import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PerformanceHeadComponent } from './performance-head.component';

describe('PerformanceHeadComponent', () => {
  let component: PerformanceHeadComponent;
  let fixture: ComponentFixture<PerformanceHeadComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PerformanceHeadComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PerformanceHeadComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
