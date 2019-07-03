import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MyPerformanceDetailComponent } from './my-performance-detail.component';

describe('MyPerformanceDetailComponent', () => {
  let component: MyPerformanceDetailComponent;
  let fixture: ComponentFixture<MyPerformanceDetailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MyPerformanceDetailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MyPerformanceDetailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
