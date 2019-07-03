import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WtRightComponent } from './wt-right.component';

describe('WtRightComponent', () => {
  let component: WtRightComponent;
  let fixture: ComponentFixture<WtRightComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WtRightComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WtRightComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
