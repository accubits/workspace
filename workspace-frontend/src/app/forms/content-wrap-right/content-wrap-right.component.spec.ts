import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ContentWrapRightComponent } from './content-wrap-right.component';

describe('ContentWrapRightComponent', () => {
  let component: ContentWrapRightComponent;
  let fixture: ComponentFixture<ContentWrapRightComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ContentWrapRightComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ContentWrapRightComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
