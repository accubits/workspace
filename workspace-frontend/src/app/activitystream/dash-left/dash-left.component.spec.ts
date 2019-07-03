import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DashLeftComponent } from './dash-left.component';

describe('DashLeftComponent', () => {
  let component: DashLeftComponent;
  let fixture: ComponentFixture<DashLeftComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DashLeftComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DashLeftComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
