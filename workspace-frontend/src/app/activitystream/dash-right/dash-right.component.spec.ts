import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DashRightComponent } from './dash-right.component';

describe('DashRightComponent', () => {
  let component: DashRightComponent;
  let fixture: ComponentFixture<DashRightComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DashRightComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DashRightComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
